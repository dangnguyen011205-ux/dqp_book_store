<?php
namespace Models;

use Core\Model;

class Order extends Model {
    /**
     * Tạo đơn hàng mới an toàn bằng Database Transaction
     */
    public function createOrder(array $orderData, array $cartItems, ?array $coupon = null): array {
        try {
            $this->beginTransaction();

            // 1. Kiểm tra tồn kho và khóa bản ghi
            foreach ($cartItems as $item) {
                $bookId = (int)$item['sach_id'];
                $qty = (int)$item['so_luong'];

                $sqlCheck = "SELECT `id`, `tieu_de`, `gia`, `so_luong_ton`, `dang_ban` 
                             FROM `sach` 
                             WHERE `id` = ? FOR UPDATE";
                $book = $this->fetchOne($sqlCheck, [$bookId]);

                if (!$book || (int)$book['dang_ban'] !== 1) {
                    $this->rollBack();
                    return [
                        'success' => false,
                        'message' => "Tác phẩm '" . ($book['tieu_de'] ?? "ID #$bookId") . "' đã ngừng kinh doanh."
                    ];
                }

                if ((int)$book['so_luong_ton'] < $qty) {
                    $this->rollBack();
                    return [
                        'success' => false,
                        'message' => "Tác phẩm '{$book['tieu_de']}' chỉ còn {$book['so_luong_ton']} cuốn trong kho, không đủ đáp ứng số lượng bạn đặt ({$qty} cuốn)."
                    ];
                }

                // Trừ tồn kho
                $sqlUpdateStock = "UPDATE `sach` SET `so_luong_ton` = `so_luong_ton` - ? WHERE `id` = ?";
                $this->execute($sqlUpdateStock, [$qty, $bookId]);
            }

            // 2. Tạo bản ghi đơn hàng
            $sqlOrder = "INSERT INTO `don_hang` 
                         (`nguoi_dung_id`, `ma_giam_gia_id`, `nguoi_nhan`, `dia_chi_giao`, `tong_tien_hang`, `tien_giam`, `phi_van_chuyen`, `tong_thanh_toan`, `trang_thai`, `ngay_dat`) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'cho_xac_nhan', NOW())";
            
            $this->execute($sqlOrder, [
                $orderData['nguoi_dung_id'],
                $coupon ? $coupon['id'] : null,
                $orderData['nguoi_nhan'],
                $orderData['dia_chi_giao'],
                $orderData['tong_tien_hang'],
                $orderData['tien_giam'],
                $orderData['phi_van_chuyen'],
                $orderData['tong_thanh_toan']
            ]);

            $orderId = $this->lastInsertId();

            // 3. Tạo chi tiết đơn hàng
            $sqlDetail = "INSERT INTO `chi_tiet_don_hang` (`don_hang_id`, `sach_id`, `so_luong`, `don_gia`) VALUES (?, ?, ?, ?)";
            foreach ($cartItems as $item) {
                $this->execute($sqlDetail, [
                    $orderId,
                    $item['sach_id'],
                    $item['so_luong'],
                    $item['gia']
                ]);
            }

            // 4. Cập nhật lượt sử dụng mã giảm giá (nếu có)
            if ($coupon) {
                $sqlCoupon = "UPDATE `ma_giam_gia` SET `da_dung` = `da_dung` + 1 WHERE `id` = ?";
                $this->execute($sqlCoupon, [$coupon['id']]);
            }

            // 5. Tạo bản ghi thanh toán
            $phuongThuc = $orderData['phuong_thuc'] ?? 'COD';
            $trangThaiThanhToan = ($phuongThuc === 'VNPAY') ? 'thanh_cong' : 'cho_thanh_toan';
            $maGiaoDich = ($phuongThuc === 'VNPAY') ? ('VNPAY' . date('YmdHis') . rand(100, 999)) : null;

            $sqlPayment = "INSERT INTO `thanh_toan` (`don_hang_id`, `phuong_thuc`, `so_tien`, `trang_thai`, `ma_giao_dich`, `ngay_thanh_toan`) 
                           VALUES (?, ?, ?, ?, ?, NOW())";
            $this->execute($sqlPayment, [
                $orderId,
                $phuongThuc,
                $orderData['tong_thanh_toan'],
                $trangThaiThanhToan,
                $maGiaoDich
            ]);

            // 6. Ghi log khởi tạo tiến trình vào lich_su_don_hang
            $ghiChu = ($phuongThuc === 'VNPAY') 
                ? "Khách hàng hoàn tất đặt đơn và thanh toán thành công qua cổng VNPAY (Mã GD: {$maGiaoDich})"
                : "Khách hàng hoàn tất đặt đơn qua website (Phương thức: Thanh toán khi nhận hàng - COD)";
            
            if (!empty($orderData['ghi_chu_khach'])) {
                $ghiChu .= " | Ghi chú: " . $orderData['ghi_chu_khach'];
            }

            $sqlHistory = "INSERT INTO `lich_su_don_hang` (`don_hang_id`, `trang_thai`, `ghi_chu`, `thoi_gian`) VALUES (?, 'cho_xac_nhan', ?, NOW())";
            $this->execute($sqlHistory, [$orderId, $ghiChu]);

            // 7. Xóa giỏ hàng
            $cartModel = new Cart();
            $cartModel->clearCart($orderData['nguoi_dung_id']);

            $this->commit();

            return [
                'success'       => true,
                'order_id'      => $orderId,
                'ma_giao_dich'  => $maGiaoDich,
                'message'       => 'Đặt hàng thành công!'
            ];
        } catch (\Throwable $e) {
            $this->rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi hệ thống trong quá trình giao tác: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách đơn hàng của một người dùng
     */
    public function getUserOrders(int $userId, ?string $status = null): array {
        $sql = "SELECT dh.*, tt.phuong_thuc, tt.trang_thai AS trang_thai_thanh_toan,
                       mgg.ma AS ma_voucher,
                       COUNT(ct.id) AS tong_so_mat_hang,
                       SUM(ct.so_luong) AS tong_so_cuon,
                       (SELECT s.tieu_de FROM `chi_tiet_don_hang` ctdh JOIN `sach` s ON ctdh.sach_id = s.id WHERE ctdh.don_hang_id = dh.id LIMIT 1) AS tieu_de_dau_tien,
                       (SELECT s.anh_bia FROM `chi_tiet_don_hang` ctdh JOIN `sach` s ON ctdh.sach_id = s.id WHERE ctdh.don_hang_id = dh.id LIMIT 1) AS anh_bia_dau_tien
                FROM `don_hang` dh
                LEFT JOIN `thanh_toan` tt ON dh.id = tt.don_hang_id
                LEFT JOIN `ma_giam_gia` mgg ON dh.ma_giam_gia_id = mgg.id
                LEFT JOIN `chi_tiet_don_hang` ct ON dh.id = ct.don_hang_id
                WHERE dh.nguoi_dung_id = ?";

        $params = [$userId];
        if (!empty($status) && $status !== 'tat_ca') {
            $sql .= " AND dh.trang_thai = ?";
            $params[] = $status;
        }

        $sql .= " GROUP BY dh.id ORDER BY dh.ngay_dat DESC";

        $orders = $this->fetchAll($sql, $params);

        // Lấy danh sách sản phẩm tóm tắt cho từng đơn
        foreach ($orders as &$ord) {
            $ord['items'] = $this->fetchAll(
                "SELECT ctdh.*, s.tieu_de, s.anh_bia 
                 FROM `chi_tiet_don_hang` ctdh 
                 JOIN `sach` s ON ctdh.sach_id = s.id 
                 WHERE ctdh.don_hang_id = ?", 
                [$ord['id']]
            );
        }

        return $orders;
    }

    /**
     * Lấy chi tiết toàn diện của một đơn hàng (kèm sản phẩm, thanh toán và timeline stepper)
     */
    public function getOrderDetail(int $orderId, ?int $userId = null): ?array {
        $sql = "SELECT dh.*, tt.phuong_thuc, tt.trang_thai AS trang_thai_thanh_toan, tt.ma_giao_dich, tt.ngay_thanh_toan,
                       mgg.ma AS ma_voucher, mgg.loai AS loai_voucher, mgg.gia_tri AS gia_tri_voucher,
                       nd.ho_ten AS khach_hang_ten, nd.email AS khach_hang_email, nd.so_dien_thoai AS khach_hang_sdt
                FROM `don_hang` dh
                LEFT JOIN `thanh_toan` tt ON dh.id = tt.don_hang_id
                LEFT JOIN `ma_giam_gia` mgg ON dh.ma_giam_gia_id = mgg.id
                JOIN `nguoi_dung` nd ON dh.nguoi_dung_id = nd.id
                WHERE dh.id = ?";
        
        $params = [$orderId];
        if ($userId !== null) {
            $sql .= " AND dh.nguoi_dung_id = ?";
            $params[] = $userId;
        }

        $order = $this->fetchOne($sql, $params);
        if (!$order) {
            return null;
        }

        // Lấy danh sách chi tiết các cuốn sách trong đơn
        $sqlItems = "SELECT ct.*, s.tieu_de, s.anh_bia, tl.ten AS the_loai_ten,
                            GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten
                     FROM `chi_tiet_don_hang` ct
                     JOIN `sach` s ON ct.sach_id = s.id
                     LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                     LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                     LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                     WHERE ct.don_hang_id = ?
                     GROUP BY ct.id";
        $order['items'] = $this->fetchAll($sqlItems, [$orderId]);

        // Lấy lịch sử hành trình vận đơn từ bảng lich_su_don_hang
        $sqlHistory = "SELECT * FROM `lich_su_don_hang` WHERE `don_hang_id` = ? ORDER BY `thoi_gian` ASC";
        $order['history'] = $this->fetchAll($sqlHistory, [$orderId]);

        return $order;
    }

    /**
     * Hủy đơn hàng an toàn bằng Database Transaction (khi đơn còn ở trạng thái cho_xac_nhan)
     */
    public function cancelOrder(int $orderId, int $userId, string $reason = ''): array {
        try {
            $this->beginTransaction();

            // 1. Kiểm tra đơn hàng có tồn tại, thuộc user và ở trạng thái cho_xac_nhan
            $sqlCheck = "SELECT * FROM `don_hang` WHERE `id` = ? AND `nguoi_dung_id` = ? FOR UPDATE";
            $order = $this->fetchOne($sqlCheck, [$orderId, $userId]);

            if (!$order) {
                $this->rollBack();
                return ['success' => false, 'message' => 'Không tìm thấy đơn hàng cần hủy.'];
            }

            if ($order['trang_thai'] !== 'cho_xac_nhan') {
                $this->rollBack();
                return [
                    'success' => false,
                    'message' => 'Đơn hàng này đã được xác nhận hoặc đang vận chuyển, không thể tự hủy trực tuyến. Quý khách vui lòng liên hệ hotline 1900 6868.'
                ];
            }

            // 2. Hoàn lại tồn kho cho các sản phẩm trong đơn
            $items = $this->fetchAll("SELECT sach_id, so_luong FROM `chi_tiet_don_hang` WHERE `don_hang_id` = ?", [$orderId]);
            foreach ($items as $it) {
                $this->execute("UPDATE `sach` SET `so_luong_ton` = `so_luong_ton` + ? WHERE `id` = ?", [$it['so_luong'], $it['sach_id']]);
            }

            // 3. Hoàn lại lượt sử dụng mã giảm giá (nếu có)
            if (!empty($order['ma_giam_gia_id'])) {
                $couponModel = new Coupon();
                $couponModel->decrementUsage((int)$order['ma_giam_gia_id']);
            }

            // 4. Cập nhật trạng thái đơn hàng sang da_huy
            $this->execute("UPDATE `don_hang` SET `trang_thai` = 'da_huy' WHERE `id` = ?", [$orderId]);

            // 5. Cập nhật trạng thái thanh toán sang that_bai (nếu chưa hoàn thành)
            $this->execute("UPDATE `thanh_toan` SET `trang_thai` = 'that_bai' WHERE `don_hang_id` = ? AND `trang_thai` = 'cho_thanh_toan'", [$orderId]);

            // 6. Ghi log hủy đơn vào lich_su_don_hang
            $ghiChuHuy = "Độc giả yêu cầu hủy đơn hàng.";
            if (!empty($reason)) {
                $ghiChuHuy .= " Lý do: " . $reason;
            }
            $this->execute("INSERT INTO `lich_su_don_hang` (`don_hang_id`, `trang_thai`, `ghi_chu`, `thoi_gian`) VALUES (?, 'da_huy', ?, NOW())", [$orderId, $ghiChuHuy]);

            $this->commit();
            return ['success' => true, 'message' => "Đã hủy đơn hàng #BK-{$orderId} thành công và hoàn trả số lượng tồn kho."];
        } catch (\Throwable $e) {
            $this->rollBack();
            return ['success' => false, 'message' => 'Lỗi khi hủy đơn hàng: ' . $e->getMessage()];
        }
    }

    /**
     * Thống kê số lượng đơn theo từng trạng thái phục vụ tabs
     */
    public function getOrderCounts(int $userId): array {
        $sql = "SELECT trang_thai, COUNT(*) AS so_luong 
                FROM `don_hang` 
                WHERE `nguoi_dung_id` = ? 
                GROUP BY trang_thai";
        $rows = $this->fetchAll($sql, [$userId]);

        $counts = [
            'tat_ca'       => 0,
            'cho_xac_nhan' => 0,
            'da_xac_nhan'  => 0,
            'dang_giao'    => 0,
            'hoan_thanh'   => 0,
            'da_huy'       => 0
        ];

        foreach ($rows as $r) {
            $st = $r['trang_thai'];
            $c = (int)$r['so_luong'];
            if (isset($counts[$st])) {
                $counts[$st] = $c;
            }
            $counts['tat_ca'] += $c;
        }

        return $counts;
    }
}
