<?php
namespace Models;

use Core\Model;

class Cart extends Model {
    public function getOrCreateCartId(int $userId): int {
        $sql = "SELECT id FROM `gio_hang` WHERE `nguoi_dung_id` = ? LIMIT 1";
        $row = $this->fetchOne($sql, [$userId]);

        if ($row) {
            return (int)$row['id'];
        }

        $this->execute("INSERT INTO `gio_hang` (`nguoi_dung_id`, `cap_nhat`) VALUES (?, NOW())", [$userId]);
        return $this->lastInsertId();
    }

    public function getItemCount(?int $userId): int {
        if (!$userId) {
            // Nếu chưa đăng nhập, đếm từ session nếu có
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += (int)($item['so_luong'] ?? 1);
                }
                return $total;
            }
            return 0;
        }

        $sql = "SELECT SUM(ct.so_luong) as tong_so_luong 
                FROM `chi_tiet_gio_hang` ct 
                JOIN `gio_hang` gh ON ct.gio_hang_id = gh.id 
                WHERE gh.nguoi_dung_id = ?";
        $res = $this->fetchOne($sql, [$userId]);
        return (int)($res['tong_so_luong'] ?? 0);
    }
}
