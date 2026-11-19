<?php
namespace Models;

require_once __DIR__ . '/../Core/Helper.php';

use Core\Model;

class Coupon extends Model {
    /**
     * Tìm mã giảm giá theo mã code (không phân biệt hoa thường)
     */
    public function findByCode(string $code): ?array {
        $sql = "SELECT * FROM `ma_giam_gia` WHERE UPPER(`ma`) = UPPER(?) LIMIT 1";
        return $this->fetchOne($sql, [trim($code)]);
    }

    /**
     * Kiểm tra tính hợp lệ của mã giảm giá với tổng tiền hàng hiện tại, phí ship và tài khoản người dùng
     * Chuẩn thương mại điện tử (Shopee):
     * - Hỗ trợ cả 2 loại: Mã Giảm Giá Đơn Hàng ('giam_gia') và Mã Miễn Phí Vận Chuyển ('mien_phi_van_chuyen')
     * - Hỗ trợ giảm theo % có mức giảm tối đa ('giam_toi_da')
     * - Kiểm tra đơn tối thiểu ('don_toi_thieu')
     * - Kiểm tra thời hạn hiệu lực, lượt dùng toàn sàn và trạng thái kho voucher cá nhân
     */
    public function validateCoupon(string $code, float $orderTotal, ?int $userId = null, float $shippingFee = 0.0): array {
        $code = trim($code);
        if (empty($code)) {
            return [
                'valid'        => false,
                'coupon'       => null,
                'loai_voucher' => 'giam_gia',
                'discount'     => 0.0,
                'message'      => 'Vui lòng nhập mã giảm giá.'
            ];
        }

        $coupon = $this->findByCode($code);
        if (!$coupon) {
            return [
                'valid'        => false,
                'coupon'       => null,
                'loai_voucher' => 'giam_gia',
                'discount'     => 0.0,
                'message'      => "Mã voucher '{$code}' không tồn tại trong hệ thống."
            ];
        }

        $loaiVoucher = $coupon['loai_voucher'] ?? 'giam_gia';

        $now = date('Y-m-d H:i:s');
        if ($now < $coupon['bat_dau']) {
            return [
                'valid'        => false,
                'coupon'       => null,
                'loai_voucher' => $loaiVoucher,
                'discount'     => 0.0,
                'message'      => "Chương trình ưu đãi của mã '{$coupon['ma']}' chưa bắt đầu."
            ];
        }

        if ($now > $coupon['ket_thuc']) {
            return [
                'valid'        => false,
                'coupon'       => null,
                'loai_voucher' => $loaiVoucher,
                'discount'     => 0.0,
                'message'      => "Mã ưu đãi '{$coupon['ma']}' đã hết hạn sử dụng."
            ];
        }

        // Kiểm tra kho voucher cá nhân của người dùng nếu đã đăng nhập
        if ($userId && $userId > 0) {
            $userVoucher = $this->fetchOne(
                "SELECT * FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ? AND `ma_giam_gia_id` = ? LIMIT 1",
                [$userId, $coupon['id']]
            );

            $maxPerUser = (int)($coupon['so_luot_toi_da'] ?? 0);

            if ($userVoucher) {
                $usedByUser = (int)($userVoucher['so_lan_da_dung'] ?? 0);
                if ($userVoucher['trang_thai'] === 'da_dung' || ($maxPerUser > 0 && $usedByUser >= $maxPerUser)) {
                    $limitText = $maxPerUser > 0 ? "tối đa {$maxPerUser} lần" : "hết lượt";
                    return [
                        'valid'        => false,
                        'coupon'       => null,
                        'loai_voucher' => $loaiVoucher,
                        'discount'     => 0.0,
                        'message'      => "Bạn đã sử dụng {$limitText} mã voucher '{$coupon['ma']}' cho các đơn hàng trước đó rồi."
                    ];
                }
            } else {
                // Người dùng nhập mã mà chưa lưu vào ví trước đó:
                // Kiểm tra xem chiến dịch quảng cáo phát hành mã này có bị giới hạn số lượng người lưu và đã hết lượt lưu chưa
                $ad = $this->fetchOne(
                    "SELECT * FROM `quang_cao` WHERE `ma_giam_gia_id` = ? AND `trang_thai` = 1 ORDER BY `id` DESC LIMIT 1",
                    [$coupon['id']]
                );
                if ($ad && (int)$ad['so_luot_luu_toi_da'] > 0) {
                    $cntSaved = (int)($this->fetchOne(
                        "SELECT COUNT(*) AS total_saved FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` = ?",
                        [$coupon['id']]
                    )['total_saved'] ?? 0);

                    if ($cntSaved >= (int)$ad['so_luot_luu_toi_da']) {
                        return [
                            'valid'        => false,
                            'coupon'       => null,
                            'loai_voucher' => $loaiVoucher,
                            'discount'     => 0.0,
                            'message'      => "Rất tiếc! Số lượng mã ưu đãi '{$coupon['ma']}' của chương trình khuyến mãi đã được lưu hết lượt."
                        ];
                    }
                }

                // Tự động thêm vào ví người dùng
                $this->execute(
                    "INSERT IGNORE INTO `voucher_nguoi_dung` (`nguoi_dung_id`, `ma_giam_gia_id`, `so_lan_da_dung`, `ngay_luu`, `trang_thai`) VALUES (?, ?, 0, NOW(), 'chua_dung')",
                    [$userId, $coupon['id']]
                );
            }
        }

        $donToiThieu = (float)$coupon['don_toi_thieu'];
        if ($orderTotal < $donToiThieu) {
            $thieu = $donToiThieu - $orderTotal;
            return [
                'valid'        => false,
                'coupon'       => null,
                'loai_voucher' => $loaiVoucher,
                'discount'     => 0.0,
                'message'      => "Mã '{$coupon['ma']}' áp dụng cho đơn từ " . format_currency($donToiThieu) . " trở lên (Cần mua thêm " . format_currency($thieu) . ")."
            ];
        }

        // Tính số tiền giảm giá chuẩn thương mại điện tử
        $giaTri = (float)$coupon['gia_tri'];
        $giamToiDa = (!empty($coupon['giam_toi_da']) && (float)$coupon['giam_toi_da'] > 0) ? (float)$coupon['giam_toi_da'] : null;
        $discount = 0.0;

        if ($loaiVoucher === 'mien_phi_van_chuyen') {
            // Giảm phí vận chuyển (Freeship)
            if ($coupon['loai'] === 'phan_tram') {
                $discount = ($shippingFee * $giaTri) / 100;
                if ($giamToiDa !== null) {
                    $discount = min($discount, $giamToiDa);
                }
            } else {
                $discount = $giaTri;
            }
            // Không thể giảm quá mức phí ship thực tế
            $discount = min($discount, $shippingFee);
        } else {
            // Giảm tiền hàng
            if ($coupon['loai'] === 'phan_tram') {
                $discount = ($orderTotal * $giaTri) / 100;
                if ($giamToiDa !== null) {
                    $discount = min($discount, $giamToiDa);
                }
            } else {
                $discount = $giaTri;
            }
            $discount = min($discount, $orderTotal);
        }

        $formattedDiscount = format_currency($discount);
        $message = ($loaiVoucher === 'mien_phi_van_chuyen')
            ? "Áp dụng thành công mã freeship '{$coupon['ma']}'! Tiết kiệm {$formattedDiscount} phí vận chuyển."
            : "Áp dụng thành công mã ưu đãi '{$coupon['ma']}'! Tiết kiệm {$formattedDiscount}.";

        return [
            'valid'        => true,
            'coupon'       => $coupon,
            'loai_voucher' => $loaiVoucher,
            'discount'     => round($discount, 2),
            'message'      => $message
        ];
    }

    /**
     * Tăng số lượt đã sử dụng của mã
     */
    public function incrementUsage(int $couponId): bool {
        $sql = "UPDATE `ma_giam_gia` SET `da_dung` = `da_dung` + 1 WHERE `id` = ?";
        return $this->execute($sql, [$couponId]);
    }

    /**
     * Giảm số lượt đã sử dụng của mã (khi hủy đơn)
     */
    public function decrementUsage(int $couponId): bool {
        $sql = "UPDATE `ma_giam_gia` SET `da_dung` = GREATEST(0, `da_dung` - 1) WHERE `id` = ?";
        return $this->execute($sql, [$couponId]);
    }

    /**
     * Khách hàng chủ động lưu voucher vào ví bằng mã code
     * Chuẩn Shopee: Cho phép người dùng nhập mã voucher tại trang Kho Voucher để lưu về ví
     */
    public function claimVoucherByCode(int $userId, string $code): array {
        if ($userId <= 0) {
            return ['success' => false, 'message' => 'Vui lòng đăng nhập để lưu voucher vào kho.'];
        }
        $code = trim($code);
        if (empty($code)) {
            return ['success' => false, 'message' => 'Vui lòng nhập mã voucher.'];
        }

        $coupon = $this->findByCode($code);
        if (!$coupon) {
            return ['success' => false, 'message' => "Mã voucher '{$code}' không tồn tại trong hệ thống."];
        }

        $now = date('Y-m-d H:i:s');
        if ($now > $coupon['ket_thuc']) {
            return ['success' => false, 'message' => "Mã voucher '{$coupon['ma']}' đã hết hạn sử dụng."];
        }

        // Kiểm tra xem đã có trong ví chưa
        $userVoucher = $this->fetchOne(
            "SELECT * FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ? AND `ma_giam_gia_id` = ? LIMIT 1",
            [$userId, $coupon['id']]
        );

        if ($userVoucher) {
            $maxPerUser = (int)($coupon['so_luot_toi_da'] ?? 0);
            $usedByUser = (int)($userVoucher['so_lan_da_dung'] ?? 0);
            if ($userVoucher['trang_thai'] === 'da_dung' || ($maxPerUser > 0 && $usedByUser >= $maxPerUser)) {
                return ['success' => false, 'message' => "Bạn đã sử dụng hết lượt của mã voucher '{$coupon['ma']}' này rồi."];
            }
            return ['success' => false, 'message' => "Mã voucher '{$coupon['ma']}' đã có sẵn trong kho của bạn rồi."];
        }

        // Kiểm tra số lượng người được lưu từ chiến dịch quảng cáo phát hành mã này (nếu có)
        $ad = $this->fetchOne(
            "SELECT * FROM `quang_cao` WHERE `ma_giam_gia_id` = ? AND `trang_thai` = 1 ORDER BY `id` DESC LIMIT 1",
            [$coupon['id']]
        );
        if ($ad && (int)$ad['so_luot_luu_toi_da'] > 0) {
            $cntSaved = (int)($this->fetchOne(
                "SELECT COUNT(*) AS total_saved FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` = ?",
                [$coupon['id']]
            )['total_saved'] ?? 0);

            if ($cntSaved >= (int)$ad['so_luot_luu_toi_da']) {
                return ['success' => false, 'message' => "Rất tiếc! Số lượng mã ưu đãi '{$coupon['ma']}' của chương trình này đã được lưu hết ({$cntSaved}/{$ad['so_luot_luu_toi_da']} lượt)."];
            }
        }

        $this->execute(
            "INSERT INTO `voucher_nguoi_dung` (`nguoi_dung_id`, `ma_giam_gia_id`, `so_lan_da_dung`, `ngay_luu`, `trang_thai`) VALUES (?, ?, 0, NOW(), 'chua_dung')",
            [$userId, $coupon['id']]
        );

        return [
            'success' => true,
            'message' => "Đã lưu thành công voucher '{$coupon['ma']}' vào kho của bạn!",
            'coupon'  => $coupon
        ];
    }

    /**
     * Lấy danh sách voucher trong kho cá nhân của một người dùng
     * Chỉ hiển thị các voucher người dùng đã chủ động bấm lưu
     * Hỗ trợ lọc theo trạng thái ($filter) và phân loại ($loaiVoucher: 'giam_gia' | 'mien_phi_van_chuyen')
     */
    public function getUserWalletCoupons(int $userId, ?string $filter = null, ?string $loaiVoucher = null): array {
        if ($userId <= 0) return [];

        $sql = "SELECT vnd.id AS wallet_id,
                       vnd.ngay_luu,
                       vnd.so_lan_da_dung,
                       vnd.trang_thai AS trang_thai_vi,
                       c.*,
                       (CASE 
                            WHEN c.loai_voucher = 'mien_phi_van_chuyen' THEN
                                (CASE WHEN c.loai = 'phan_tram' THEN CONCAT('Giảm ', CAST(c.gia_tri AS SIGNED), '% Ship')
                                      ELSE CONCAT('Freeship ', REPLACE(FORMAT(c.gia_tri, 0), ',', '.'), 'đ') END)
                            ELSE
                                (CASE WHEN c.loai = 'phan_tram' THEN 
                                    CONCAT('Giảm ', CAST(c.gia_tri AS SIGNED), '%', 
                                           CASE WHEN c.giam_toi_da > 0 THEN CONCAT(' (Tối đa ', REPLACE(FORMAT(c.giam_toi_da, 0), ',', '.'), 'đ)') ELSE '' END)
                                      ELSE CONCAT('Giảm ', REPLACE(FORMAT(c.gia_tri, 0), ',', '.'), 'đ') END)
                        END) AS text_giam,
                       (CASE 
                            WHEN vnd.trang_thai = 'da_dung' THEN 'da_dung'
                            WHEN c.ket_thuc < NOW() THEN 'het_han'
                            WHEN c.bat_dau > NOW() THEN 'chua_bat_dau'
                            ELSE 'kha_dung'
                        END) AS tinh_trang
                FROM `voucher_nguoi_dung` vnd
                JOIN `ma_giam_gia` c ON vnd.ma_giam_gia_id = c.id
                WHERE vnd.nguoi_dung_id = ?";

        $params = [$userId];

        if ($filter === 'available' || $filter === 'kha_dung') {
            $sql .= " AND vnd.trang_thai = 'chua_dung' AND c.bat_dau <= NOW() AND c.ket_thuc >= NOW()";
        } elseif ($filter === 'used' || $filter === 'da_dung') {
            $sql .= " AND vnd.trang_thai = 'da_dung'";
        } elseif ($filter === 'expired' || $filter === 'het_han') {
            $sql .= " AND vnd.trang_thai = 'chua_dung' AND c.ket_thuc < NOW()";
        }

        if (!empty($loaiVoucher) && in_array($loaiVoucher, ['giam_gia', 'mien_phi_van_chuyen'], true)) {
            $sql .= " AND c.loai_voucher = ?";
            $params[] = $loaiVoucher;
        }

        $sql .= " ORDER BY (CASE WHEN vnd.trang_thai = 'chua_dung' AND c.ket_thuc >= NOW() THEN 0 ELSE 1 END), c.gia_tri DESC, vnd.id DESC";

        $rows = $this->fetchAll($sql, $params);

        // Bổ sung metadata định dạng cho view
        foreach ($rows as &$r) {
            $isFreeship = (($r['loai_voucher'] ?? 'giam_gia') === 'mien_phi_van_chuyen');
            $r['badge_text'] = $isFreeship ? 'Freeship Xtra' : 'Mã Giảm Giá';
            $r['badge_color'] = $isFreeship ? 'emerald' : 'amber';
            $r['don_toi_thieu_formatted'] = format_currency((float)$r['don_toi_thieu']);
            $r['hsd_formatted'] = date('d/m/Y', strtotime($r['ket_thuc']));
            $maxPerUser = (int)$r['so_luot_toi_da'];
            $usedByUser = (int)($r['so_lan_da_dung'] ?? 0);
            $r['remain_uses'] = ($maxPerUser > 0) ? max(0, $maxPerUser - $usedByUser) : null;
            if (empty($r['tieu_de'])) {
                $r['tieu_de'] = $isFreeship ? 'Miễn phí vận chuyển cho đơn từ ' . $r['don_toi_thieu_formatted'] : $r['text_giam'] . ' cho đơn từ ' . $r['don_toi_thieu_formatted'];
            }
        }

        return $rows;
    }

    /**
     * Lấy danh sách voucher đã sử dụng kèm chi tiết đơn hàng tương ứng
     * Phục vụ mục "Voucher đã sử dụng" trong kho voucher cá nhân
     * Chuẩn Shopee: Liên kết cả mã giảm giá đơn hàng và mã freeship
     */
    public function getUsedCouponsWithOrders(int $userId): array {
        if ($userId <= 0) return [];

        $sql = "SELECT vnd.id AS wallet_id,
                       vnd.ngay_luu,
                       vnd.so_lan_da_dung,
                       vnd.trang_thai AS trang_thai_vi,
                       c.*,
                       (CASE 
                            WHEN c.loai_voucher = 'mien_phi_van_chuyen' THEN
                                (CASE WHEN c.loai = 'phan_tram' THEN CONCAT('Giảm ', CAST(c.gia_tri AS SIGNED), '% Ship')
                                      ELSE CONCAT('Freeship ', REPLACE(FORMAT(c.gia_tri, 0), ',', '.'), 'đ') END)
                            ELSE
                                (CASE WHEN c.loai = 'phan_tram' THEN 
                                    CONCAT('Giảm ', CAST(c.gia_tri AS SIGNED), '%', 
                                           CASE WHEN c.giam_toi_da > 0 THEN CONCAT(' (Tối đa ', REPLACE(FORMAT(c.giam_toi_da, 0), ',', '.'), 'đ)') ELSE '' END)
                                      ELSE CONCAT('Giảm ', REPLACE(FORMAT(c.gia_tri, 0), ',', '.'), 'đ') END)
                        END) AS text_giam,
                       'da_dung' AS tinh_trang,
                       dh.id AS don_hang_id,
                       dh.ngay_dat AS ngay_dung,
                       dh.tong_thanh_toan,
                       (CASE WHEN c.loai_voucher = 'mien_phi_van_chuyen' THEN dh.tien_giam_ship ELSE dh.tien_giam END) AS so_tien_da_giam,
                       dh.trang_thai AS don_hang_trang_thai
                FROM `voucher_nguoi_dung` vnd
                JOIN `ma_giam_gia` c ON vnd.ma_giam_gia_id = c.id
                LEFT JOIN `don_hang` dh ON dh.id = (
                    SELECT MAX(d2.id) 
                    FROM `don_hang` d2 
                    WHERE (d2.ma_giam_gia_id = c.id OR d2.ma_freeship_id = c.id) 
                      AND d2.nguoi_dung_id = vnd.nguoi_dung_id 
                      AND d2.trang_thai != 'da_huy'
                )
                WHERE vnd.nguoi_dung_id = ? AND vnd.trang_thai = 'da_dung'
                ORDER BY vnd.id DESC";
        $rows = $this->fetchAll($sql, [$userId]);

        foreach ($rows as &$r) {
            $isFreeship = (($r['loai_voucher'] ?? 'giam_gia') === 'mien_phi_van_chuyen');
            $r['badge_text'] = $isFreeship ? 'Freeship Xtra' : 'Mã Giảm Giá';
            $r['badge_color'] = $isFreeship ? 'emerald' : 'amber';
            $r['don_toi_thieu_formatted'] = format_currency((float)$r['don_toi_thieu']);
            $r['hsd_formatted'] = date('d/m/Y', strtotime($r['ket_thuc']));
            if (empty($r['tieu_de'])) {
                $r['tieu_de'] = $isFreeship ? 'Miễn phí vận chuyển cho đơn từ ' . $r['don_toi_thieu_formatted'] : $r['text_giam'] . ' cho đơn từ ' . $r['don_toi_thieu_formatted'];
            }
        }

        return $rows;
    }

    /**
     * Lấy danh sách các mã giảm giá đang còn hiệu lực
     */
    public function getActiveCoupons(): array {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM `ma_giam_gia` 
                WHERE `bat_dau` <= ? AND `ket_thuc` >= ?
                ORDER BY `gia_tri` DESC";
        return $this->fetchAll($sql, [$now, $now]);
    }

    /**
     * Lấy danh sách thống nhất toàn bộ Voucher / Mã giảm giá khả dụng
     * Nếu user đã đăng nhập: lấy danh sách voucher KHẢ DỤNG từ kho voucher riêng của user đó
     */
    public function getUnifiedCoupons(?int $userId = null): array {
        if ($userId && $userId > 0) {
            return $this->getUserWalletCoupons($userId, 'available');
        }

        $now = date('Y-m-d H:i:s');
        $sql = "SELECT c.*, 
                       (CASE WHEN c.loai = 'phan_tram' THEN CONCAT('Giảm ', CAST(c.gia_tri AS SIGNED), '%') 
                             ELSE CONCAT('Giảm ', REPLACE(FORMAT(c.gia_tri, 0), ',', '.'), 'đ') END) AS text_giam,
                       'kha_dung' AS tinh_trang,
                       'chua_dung' AS trang_thai_vi
                FROM `ma_giam_gia` c
                WHERE c.bat_dau <= ? AND c.ket_thuc >= ?
                ORDER BY c.gia_tri DESC, c.id DESC";
        return $this->fetchAll($sql, [$now, $now]);
    }

    /**
     * Lấy toàn bộ danh sách mã giảm giá phục vụ quản trị Admin kèm bộ lọc
     */
    public function getAll(?string $search = '', ?string $status = '', ?string $type = ''): array {
        $sql = "SELECT m.*, 
                       COUNT(DISTINCT CASE WHEN d.trang_thai != 'da_huy' THEN d.id ELSE NULL END) AS so_don_hang,
                       (SELECT COUNT(DISTINCT vnd.nguoi_dung_id) FROM `voucher_nguoi_dung` vnd WHERE vnd.ma_giam_gia_id = m.id) AS so_nguoi_luu,
                       COALESCE(SUM(CASE 
                           WHEN d.trang_thai = 'da_huy' THEN 0 
                           WHEN m.loai_voucher = 'mien_phi_van_chuyen' THEN (CASE WHEN d.tien_giam_ship > 0 THEN d.tien_giam_ship ELSE d.tien_giam END) 
                           ELSE d.tien_giam 
                       END), 0) AS tong_tien_giam_thuc_te,
                       CASE 
                           WHEN m.ket_thuc < NOW() THEN 'het_han'
                           WHEN m.bat_dau > NOW() THEN 'chua_bat_dau'
                           ELSE 'con_han'
                       END AS trang_thai_hieu_luc
                FROM `ma_giam_gia` m
                LEFT JOIN `don_hang` d ON (m.id = d.ma_giam_gia_id OR m.id = d.ma_freeship_id)
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND UPPER(m.ma) LIKE UPPER(?)";
            $params[] = '%' . trim($search) . '%';
        }

        if (!empty($type) && in_array($type, ['phan_tram', 'tien_mat'])) {
            $sql .= " AND m.loai = ?";
            $params[] = $type;
        }

        if (!empty($status)) {
            if ($status === 'con_han') {
                $sql .= " AND m.bat_dau <= NOW() AND m.ket_thuc >= NOW()";
            } elseif ($status === 'chua_bat_dau') {
                $sql .= " AND m.bat_dau > NOW()";
            } elseif ($status === 'het_han') {
                $sql .= " AND m.ket_thuc < NOW()";
            }
        }

        $sql .= " GROUP BY m.id ORDER BY m.id DESC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Thống kê KPI tổng quan về mã giảm giá
     */
    public function getStatistics(): array {
        $now = date('Y-m-d H:i:s');

        // Tổng số voucher
        $rowTotal = $this->fetchOne("SELECT COUNT(*) AS total FROM `ma_giam_gia`");
        $total = (int)($rowTotal['total'] ?? 0);

        // Đang hiệu lực (trong thời gian bắt đầu & kết thúc)
        $rowActive = $this->fetchOne(
            "SELECT COUNT(*) AS active FROM `ma_giam_gia` 
             WHERE `bat_dau` <= ? AND `ket_thuc` >= ?",
            [$now, $now]
        );
        $active = (int)($rowActive['active'] ?? 0);

        // Đã hết hạn
        $rowExpired = $this->fetchOne(
            "SELECT COUNT(*) AS expired FROM `ma_giam_gia` WHERE `ket_thuc` < ?",
            [$now]
        );
        $expired = (int)($rowExpired['expired'] ?? 0);

        // Chưa bắt đầu
        $rowPending = $this->fetchOne(
            "SELECT COUNT(*) AS pending FROM `ma_giam_gia` WHERE `bat_dau` > ?",
            [$now]
        );
        $pending = (int)($rowPending['pending'] ?? 0);

        // Tổng lượt đã sử dụng trên toàn sàn
        $rowTotalUsed = $this->fetchOne("SELECT COALESCE(SUM(`da_dung`), 0) AS total_used FROM `ma_giam_gia`");
        $totalUsed = (int)($rowTotalUsed['total_used'] ?? 0);

        // Tổng tiền thực tế đã giảm giá cho khách (gồm cả giảm hàng và freeship, không tính đơn hủy)
        $rowDiscount = $this->fetchOne(
            "SELECT COALESCE(SUM(tien_giam + tien_giam_ship), 0) AS total_discount 
             FROM `don_hang` 
             WHERE (ma_giam_gia_id IS NOT NULL OR ma_freeship_id IS NOT NULL) AND `trang_thai` != 'da_huy'"
        );
        $totalDiscount = (float)($rowDiscount['total_discount'] ?? 0.0);

        return [
            'total'          => $total,
            'active'         => $active,
            'expired'        => $expired,
            'pending'        => $pending,
            'total_used'     => $totalUsed,
            'total_discount' => $totalDiscount
        ];
    }

    /**
     * Tìm chi tiết voucher theo ID kèm số lượng đơn hàng liên kết và số người lưu
     */
    public function findById(int $id): ?array {
        $sql = "SELECT m.*, 
                       COUNT(DISTINCT CASE WHEN d.trang_thai != 'da_huy' THEN d.id ELSE NULL END) AS so_don_hang,
                       (SELECT COUNT(DISTINCT vnd.nguoi_dung_id) FROM `voucher_nguoi_dung` vnd WHERE vnd.ma_giam_gia_id = m.id) AS so_nguoi_luu
                FROM `ma_giam_gia` m 
                LEFT JOIN `don_hang` d ON (m.id = d.ma_giam_gia_id OR m.id = d.ma_freeship_id) 
                WHERE m.id = ? 
                GROUP BY m.id 
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Đồng bộ số lượt đã dùng (da_dung) của voucher khớp với số đơn hàng thực tế (trang_thai != 'da_huy')
     */
    public function syncUsage(?int $id = null): void {
        if ($id !== null && $id > 0) {
            $this->execute(
                "UPDATE `ma_giam_gia` m 
                 SET `da_dung` = (
                     SELECT COUNT(DISTINCT d.id) 
                     FROM `don_hang` d 
                     WHERE (d.ma_giam_gia_id = m.id OR d.ma_freeship_id = m.id) 
                       AND d.trang_thai != 'da_huy'
                 )
                 WHERE m.id = ?",
                [$id]
            );
        } else {
            $this->execute(
                "UPDATE `ma_giam_gia` m 
                 SET `da_dung` = (
                     SELECT COUNT(DISTINCT d.id) 
                     FROM `don_hang` d 
                     WHERE (d.ma_giam_gia_id = m.id OR d.ma_freeship_id = m.id) 
                       AND d.trang_thai != 'da_huy'
                 )"
            );
        }
    }

    /**
     * Kiểm tra mã code đã tồn tại hay chưa (không phân biệt hoa thường, loại trừ ID nếu có)
     */
    public function checkCodeExists(string $code, ?int $excludeId = null): bool {
        $code = trim($code);
        if ($excludeId !== null && $excludeId > 0) {
            $sql = "SELECT COUNT(*) AS cnt FROM `ma_giam_gia` WHERE UPPER(`ma`) = UPPER(?) AND `id` != ?";
            $row = $this->fetchOne($sql, [$code, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) AS cnt FROM `ma_giam_gia` WHERE UPPER(`ma`) = UPPER(?)";
            $row = $this->fetchOne($sql, [$code]);
        }
        return ((int)($row['cnt'] ?? 0)) > 0;
    }

    /**
     * Thêm mới một mã giảm giá vào CSDL
     */
    public function create(array $data): int {
        $sql = "INSERT INTO `ma_giam_gia` (`ma`, `tieu_de`, `loai_voucher`, `loai`, `gia_tri`, `giam_toi_da`, `don_toi_thieu`, `bat_dau`, `ket_thuc`, `so_luot_toi_da`, `da_dung`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
        $params = [
            strtoupper(trim($data['ma'])),
            !empty($data['tieu_de']) ? trim($data['tieu_de']) : null,
            $data['loai_voucher'] ?? 'giam_gia',
            $data['loai'] ?? 'tien_mat',
            (float)$data['gia_tri'],
            (!empty($data['giam_toi_da']) && (float)$data['giam_toi_da'] > 0) ? (float)$data['giam_toi_da'] : null,
            (float)($data['don_toi_thieu'] ?? 0),
            $data['bat_dau'],
            $data['ket_thuc'],
            (int)($data['so_luot_toi_da'] ?? 0)
        ];
        $this->execute($sql, $params);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Cập nhật thông tin mã giảm giá
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE `ma_giam_gia` 
                SET `ma` = ?, `tieu_de` = ?, `loai_voucher` = ?, `loai` = ?, `gia_tri` = ?, `giam_toi_da` = ?, `don_toi_thieu` = ?, `bat_dau` = ?, `ket_thuc` = ?, `so_luot_toi_da` = ? 
                WHERE `id` = ?";
        $params = [
            strtoupper(trim($data['ma'])),
            !empty($data['tieu_de']) ? trim($data['tieu_de']) : null,
            $data['loai_voucher'] ?? 'giam_gia',
            $data['loai'] ?? 'tien_mat',
            (float)$data['gia_tri'],
            (!empty($data['giam_toi_da']) && (float)$data['giam_toi_da'] > 0) ? (float)$data['giam_toi_da'] : null,
            (float)($data['don_toi_thieu'] ?? 0),
            $data['bat_dau'],
            $data['ket_thuc'],
            (int)($data['so_luot_toi_da'] ?? 0),
            $id
        ];
        return $this->execute($sql, $params);
    }

    /**
     * Xóa mã giảm giá
     */
    public function delete(int $id): array {
        $coupon = $this->findById($id);
        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại trong hệ thống.'
            ];
        }

        $sql = "DELETE FROM `ma_giam_gia` WHERE `id` = ?";
        $ok = $this->execute($sql, [$id]);
        if ($ok) {
            return [
                'success' => true,
                'message' => "Đã xóa mã giảm giá '{$coupon['ma']}' thành công!"
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi khi xóa mã giảm giá khỏi cơ sở dữ liệu.'
        ];
    }
}
