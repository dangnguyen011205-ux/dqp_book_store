<?php
namespace Models;

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
     * Kiểm tra tính hợp lệ của mã giảm giá với tổng tiền hàng hiện tại
     */
    public function validateCoupon(string $code, float $orderTotal): array {
        $code = trim($code);
        if (empty($code)) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => 'Vui lòng nhập mã giảm giá.'
            ];
        }

        $coupon = $this->findByCode($code);
        if (!$coupon) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => "Mã giảm giá '{$code}' không tồn tại trong hệ thống."
            ];
        }

        $now = date('Y-m-d H:i:s');
        if ($now < $coupon['bat_dau']) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => "Chương trình ưu đãi của mã '{$coupon['ma']}' chưa bắt đầu."
            ];
        }

        if ($now > $coupon['ket_thuc']) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => "Mã giảm giá '{$coupon['ma']}' đã hết hạn sử dụng."
            ];
        }

        if ($coupon['so_luot_toi_da'] > 0 && $coupon['da_dung'] >= $coupon['so_luot_toi_da']) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => "Mã giảm giá '{$coupon['ma']}' đã hết lượt sử dụng."
            ];
        }

        $donToiThieu = (float)$coupon['don_toi_thieu'];
        if ($orderTotal < $donToiThieu) {
            return [
                'valid'    => false,
                'coupon'   => null,
                'discount' => 0.0,
                'message'  => "Mã '{$coupon['ma']}' chỉ áp dụng cho đơn hàng từ " . format_currency($donToiThieu) . " trở lên (giỏ hàng hiện tại: " . format_currency($orderTotal) . ")."
            ];
        }

        // Tính số tiền giảm giá
        $giaTri = (float)$coupon['gia_tri'];
        $discount = 0.0;

        if ($coupon['loai'] === 'phan_tram') {
            $discount = round(($orderTotal * $giaTri) / 100, 2);
        } else {
            // Giảm tiền mặt
            $discount = min($giaTri, $orderTotal);
        }

        return [
            'valid'    => true,
            'coupon'   => $coupon,
            'discount' => $discount,
            'message'  => "Áp dụng thành công mã '{$coupon['ma']}'! Tiết kiệm " . format_currency($discount) . "."
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
     * Lấy danh sách các mã giảm giá đang còn hiệu lực
     */
    public function getActiveCoupons(): array {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM `ma_giam_gia` 
                WHERE `bat_dau` <= ? AND `ket_thuc` >= ? AND (`so_luot_toi_da` = 0 OR `da_dung` < `so_luot_toi_da`)
                ORDER BY `gia_tri` DESC";
        return $this->fetchAll($sql, [$now, $now]);
    }

    /**
     * Lấy toàn bộ danh sách mã giảm giá phục vụ quản trị Admin kèm bộ lọc
     */
    public function getAll(?string $search = '', ?string $status = '', ?string $type = ''): array {
        $sql = "SELECT m.*, 
                       COUNT(d.id) AS so_don_hang,
                       COALESCE(SUM(d.tien_giam), 0) AS tong_tien_giam_thuc_te,
                       CASE 
                           WHEN m.ket_thuc < NOW() THEN 'het_han'
                           WHEN m.bat_dau > NOW() THEN 'chua_bat_dau'
                           WHEN m.so_luot_toi_da > 0 AND m.da_dung >= m.so_luot_toi_da THEN 'het_luot'
                           ELSE 'con_han'
                       END AS trang_thai_hieu_luc
                FROM `ma_giam_gia` m
                LEFT JOIN `don_hang` d ON m.id = d.ma_giam_gia_id
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
                $sql .= " AND m.bat_dau <= NOW() AND m.ket_thuc >= NOW() AND (m.so_luot_toi_da = 0 OR m.da_dung < m.so_luot_toi_da)";
            } elseif ($status === 'chua_bat_dau') {
                $sql .= " AND m.bat_dau > NOW()";
            } elseif ($status === 'het_han') {
                $sql .= " AND m.ket_thuc < NOW()";
            } elseif ($status === 'het_luot') {
                $sql .= " AND m.so_luot_toi_da > 0 AND m.da_dung >= m.so_luot_toi_da";
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

        // Đang hiệu lực (sẵn sàng áp dụng)
        $rowActive = $this->fetchOne(
            "SELECT COUNT(*) AS active FROM `ma_giam_gia` 
             WHERE `bat_dau` <= ? AND `ket_thuc` >= ? AND (`so_luot_toi_da` = 0 OR `da_dung` < `so_luot_toi_da`)",
            [$now, $now]
        );
        $active = (int)($rowActive['active'] ?? 0);

        // Đã hết hạn
        $rowExpired = $this->fetchOne(
            "SELECT COUNT(*) AS expired FROM `ma_giam_gia` WHERE `ket_thuc` < ?",
            [$now]
        );
        $expired = (int)($rowExpired['expired'] ?? 0);

        // Hết lượt sử dụng (chưa hết hạn nhưng đã đạt số lượt tối đa)
        $rowMaxUsed = $this->fetchOne(
            "SELECT COUNT(*) AS max_used FROM `ma_giam_gia` 
             WHERE `so_luot_toi_da` > 0 AND `da_dung` >= `so_luot_toi_da` AND `ket_thuc` >= ?",
            [$now]
        );
        $maxUsed = (int)($rowMaxUsed['max_used'] ?? 0);

        // Tổng lượt đã sử dụng trên toàn sàn
        $rowTotalUsed = $this->fetchOne("SELECT COALESCE(SUM(`da_dung`), 0) AS total_used FROM `ma_giam_gia`");
        $totalUsed = (int)($rowTotalUsed['total_used'] ?? 0);

        // Tổng tiền thực tế đã giảm giá cho khách
        $rowDiscount = $this->fetchOne(
            "SELECT COALESCE(SUM(`tien_giam`), 0) AS total_discount 
             FROM `don_hang` 
             WHERE `ma_giam_gia_id` IS NOT NULL AND `trang_thai` != 'da_huy'"
        );
        $totalDiscount = (float)($rowDiscount['total_discount'] ?? 0.0);

        return [
            'total'          => $total,
            'active'         => $active,
            'expired'        => $expired,
            'max_used'       => $maxUsed,
            'total_used'     => $totalUsed,
            'total_discount' => $totalDiscount
        ];
    }

    /**
     * Tìm chi tiết voucher theo ID kèm số lượng đơn hàng liên kết
     */
    public function findById(int $id): ?array {
        $sql = "SELECT m.*, COUNT(d.id) AS so_don_hang 
                FROM `ma_giam_gia` m 
                LEFT JOIN `don_hang` d ON m.id = d.ma_giam_gia_id 
                WHERE m.id = ? 
                GROUP BY m.id 
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
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
        $sql = "INSERT INTO `ma_giam_gia` (`ma`, `loai`, `gia_tri`, `don_toi_thieu`, `bat_dau`, `ket_thuc`, `so_luot_toi_da`, `da_dung`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $params = [
            strtoupper(trim($data['ma'])),
            $data['loai'],
            (float)$data['gia_tri'],
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
                SET `ma` = ?, `loai` = ?, `gia_tri` = ?, `don_toi_thieu` = ?, `bat_dau` = ?, `ket_thuc` = ?, `so_luot_toi_da` = ? 
                WHERE `id` = ?";
        $params = [
            strtoupper(trim($data['ma'])),
            $data['loai'],
            (float)$data['gia_tri'],
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
