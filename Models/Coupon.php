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
}
