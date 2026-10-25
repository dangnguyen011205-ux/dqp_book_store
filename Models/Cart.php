<?php
namespace Models;

use Core\Model;

class Cart extends Model {
    /**
     * Lấy hoặc tạo ID giỏ hàng cho người dùng đã đăng nhập
     */
    public function getOrCreateCartId(int $userId): int {
        $sql = "SELECT id FROM `gio_hang` WHERE `nguoi_dung_id` = ? LIMIT 1";
        $row = $this->fetchOne($sql, [$userId]);

        if ($row) {
            return (int)$row['id'];
        }

        $this->execute("INSERT INTO `gio_hang` (`nguoi_dung_id`, `cap_nhat`) VALUES (?, NOW())", [$userId]);
        return $this->lastInsertId();
    }

    /**
     * Đếm tổng số lượng sản phẩm trong giỏ hàng (hiển thị trên badge header)
     */
    public function getItemCount(?int $userId): int {
        if (!$userId) {
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

    /**
     * Lấy danh sách chi tiết các cuốn sách trong giỏ hàng
     */
    public function getItems(?int $userId): array {
        if ($userId) {
            $cartId = $this->getOrCreateCartId($userId);
            $sql = "SELECT ct.sach_id, ct.so_luong, s.tieu_de, s.gia, s.anh_bia, s.so_luong_ton, s.dang_ban,
                           tl.ten AS the_loai_ten,
                           GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                           (ct.so_luong * s.gia) AS thanh_tien
                    FROM `chi_tiet_gio_hang` ct
                    JOIN `sach` s ON ct.sach_id = s.id
                    LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                    LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                    LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                    WHERE ct.gio_hang_id = ? AND s.dang_ban = 1
                    GROUP BY ct.sach_id
                    ORDER BY s.id DESC";
            return $this->fetchAll($sql, [$cartId]);
        }

        // Khách vãng lai: Đọc từ $_SESSION['cart']
        $items = [];
        if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $bookIds = array_keys($_SESSION['cart']);
            if (!empty($bookIds)) {
                $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
                $sql = "SELECT s.id AS sach_id, s.tieu_de, s.gia, s.anh_bia, s.so_luong_ton, s.dang_ban,
                               tl.ten AS the_loai_ten,
                               GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten
                        FROM `sach` s
                        LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                        LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                        LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                        WHERE s.id IN ($placeholders) AND s.dang_ban = 1
                        GROUP BY s.id";
                $books = $this->fetchAll($sql, $bookIds);
                
                foreach ($books as $b) {
                    $qty = (int)($_SESSION['cart'][$b['sach_id']]['so_luong'] ?? 1);
                    $qty = min($qty, (int)$b['so_luong_ton']); // Không vượt tồn kho
                    $b['so_luong'] = $qty;
                    $b['thanh_tien'] = $qty * (float)$b['gia'];
                    $items[] = $b;
                }
            }
        }
        return $items;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addItem(?int $userId, int $bookId, int $quantity = 1): array {
        if ($bookId <= 0 || $quantity <= 0) {
            return ['success' => false, 'message' => 'Thông tin sản phẩm hoặc số lượng không hợp lệ.'];
        }

        // Kiểm tra sách có tồn tại và tồn kho không
        $book = $this->fetchOne("SELECT id, tieu_de, gia, so_luong_ton, dang_ban FROM `sach` WHERE `id` = ?", [$bookId]);
        if (!$book || (int)$book['dang_ban'] !== 1) {
            return ['success' => false, 'message' => 'Tác phẩm không tồn tại hoặc đã ngừng kinh doanh.'];
        }

        $stock = (int)$book['so_luong_ton'];
        if ($stock <= 0) {
            return ['success' => false, 'message' => "Tác phẩm '{$book['tieu_de']}' hiện đã hết hàng trong kho."];
        }

        if ($userId) {
            $cartId = $this->getOrCreateCartId($userId);
            $existing = $this->fetchOne("SELECT so_luong FROM `chi_tiet_gio_hang` WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$cartId, $bookId]);
            $currentQty = $existing ? (int)$existing['so_luong'] : 0;
            $newQty = $currentQty + $quantity;

            if ($newQty > $stock) {
                return [
                    'success' => false,
                    'message' => "Bạn đã có {$currentQty} cuốn trong giỏ. Tồn kho chỉ còn {$stock} cuốn."
                ];
            }

            if ($existing) {
                $this->execute("UPDATE `chi_tiet_gio_hang` SET `so_luong` = ? WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$newQty, $cartId, $bookId]);
            } else {
                $this->execute("INSERT INTO `chi_tiet_gio_hang` (`gio_hang_id`, `sach_id`, `so_luong`) VALUES (?, ?, ?)", [$cartId, $bookId, $newQty]);
            }
            $this->execute("UPDATE `gio_hang` SET `cap_nhat` = NOW() WHERE `id` = ?", [$cartId]);
        } else {
            // Lưu vào session cho khách vãng lai
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $currentQty = isset($_SESSION['cart'][$bookId]) ? (int)$_SESSION['cart'][$bookId]['so_luong'] : 0;
            $newQty = $currentQty + $quantity;

            if ($newQty > $stock) {
                return [
                    'success' => false,
                    'message' => "Bạn đã có {$currentQty} cuốn trong giỏ. Tồn kho chỉ còn {$stock} cuốn."
                ];
            }

            $_SESSION['cart'][$bookId] = [
                'sach_id'  => $bookId,
                'so_luong' => $newQty
            ];
        }

        return [
            'success'   => true,
            'message'   => "Đã thêm '{$book['tieu_de']}' vào giỏ hàng thành công!",
            'itemCount' => $this->getItemCount($userId)
        ];
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ
     */
    public function updateItem(?int $userId, int $bookId, int $quantity): array {
        if ($bookId <= 0) {
            return ['success' => false, 'message' => 'Sản phẩm không hợp lệ.'];
        }

        if ($quantity <= 0) {
            return $this->removeItem($userId, $bookId);
        }

        // Kiểm tra tồn kho
        $book = $this->fetchOne("SELECT id, tieu_de, so_luong_ton FROM `sach` WHERE `id` = ?", [$bookId]);
        if (!$book) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại.'];
        }

        $stock = (int)$book['so_luong_ton'];
        if ($quantity > $stock) {
            return [
                'success' => false,
                'message' => "Kho chỉ còn {$stock} cuốn '{$book['tieu_de']}', không thể chọn {$quantity} cuốn."
            ];
        }

        if ($userId) {
            $cartId = $this->getOrCreateCartId($userId);
            $this->execute("UPDATE `chi_tiet_gio_hang` SET `so_luong` = ? WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$quantity, $cartId, $bookId]);
            $this->execute("UPDATE `gio_hang` SET `cap_nhat` = NOW() WHERE `id` = ?", [$cartId]);
        } else {
            if (isset($_SESSION['cart'][$bookId])) {
                $_SESSION['cart'][$bookId]['so_luong'] = $quantity;
            }
        }

        return ['success' => true, 'message' => 'Đã cập nhật số lượng thành công!'];
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng
     */
    public function removeItem(?int $userId, int $bookId): array {
        if ($userId) {
            $cartId = $this->getOrCreateCartId($userId);
            $this->execute("DELETE FROM `chi_tiet_gio_hang` WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$cartId, $bookId]);
            $this->execute("UPDATE `gio_hang` SET `cap_nhat` = NOW() WHERE `id` = ?", [$cartId]);
        } else {
            if (isset($_SESSION['cart'][$bookId])) {
                unset($_SESSION['cart'][$bookId]);
            }
        }
        return ['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'];
    }

    /**
     * Xóa sạch toàn bộ giỏ hàng
     */
    public function clearCart(?int $userId): bool {
        if ($userId) {
            $cartId = $this->getOrCreateCartId($userId);
            $this->execute("DELETE FROM `chi_tiet_gio_hang` WHERE `gio_hang_id` = ?", [$cartId]);
            $this->execute("UPDATE `gio_hang` SET `cap_nhat` = NOW() WHERE `id` = ?", [$cartId]);
        } else {
            unset($_SESSION['cart']);
        }
        return true;
    }

    /**
     * Gộp giỏ hàng session của khách vào CSDL khi đăng nhập
     */
    public function mergeSessionCart(int $userId): void {
        if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            return;
        }

        $cartId = $this->getOrCreateCartId($userId);
        foreach ($_SESSION['cart'] as $bookId => $item) {
            $bookId = (int)$bookId;
            $qty = (int)($item['so_luong'] ?? 1);
            if ($bookId <= 0 || $qty <= 0) continue;

            $existing = $this->fetchOne("SELECT so_luong FROM `chi_tiet_gio_hang` WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$cartId, $bookId]);
            if ($existing) {
                $newQty = (int)$existing['so_luong'] + $qty;
                $this->execute("UPDATE `chi_tiet_gio_hang` SET `so_luong` = ? WHERE `gio_hang_id` = ? AND `sach_id` = ?", [$newQty, $cartId, $bookId]);
            } else {
                $this->execute("INSERT INTO `chi_tiet_gio_hang` (`gio_hang_id`, `sach_id`, `so_luong`) VALUES (?, ?, ?)", [$cartId, $bookId, $qty]);
            }
        }
        unset($_SESSION['cart']);
    }
}
