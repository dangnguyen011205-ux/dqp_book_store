<?php
namespace Models;

use Core\Model;

class Author extends Model {
    /**
     * Lấy toàn bộ tác giả kèm số lượng tác phẩm và tìm kiếm
     */
    public function getAll(?string $search = null): array {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where = "(tg.ten LIKE ? OR tg.tieu_su LIKE ?)";
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
        }

        $sql = "SELECT tg.*, COUNT(DISTINCT stg.sach_id) AS tong_sach
                FROM `tac_gia` tg
                LEFT JOIN `sach_tac_gia` stg ON tg.id = stg.tac_gia_id
                WHERE $where
                GROUP BY tg.id
                ORDER BY tong_sach DESC, tg.ten ASC";
        return $this->fetchAll($sql, $params);
    }

    public function getFeatured(int $limit = 6): array {
        $sql = "SELECT tg.*, COUNT(DISTINCT stg.sach_id) AS tong_sach
                FROM `tac_gia` tg
                LEFT JOIN `sach_tac_gia` stg ON tg.id = stg.tac_gia_id
                GROUP BY tg.id
                ORDER BY tong_sach DESC, tg.id ASC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $sql = "SELECT tg.*, COUNT(DISTINCT stg.sach_id) AS tong_sach
                FROM `tac_gia` tg
                LEFT JOIN `sach_tac_gia` stg ON tg.id = stg.tac_gia_id
                WHERE tg.id = ? 
                GROUP BY tg.id
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `tac_gia` (`ten`, `tieu_su`) VALUES (?, ?)";
        $this->execute($sql, [trim($data['ten']), !empty($data['tieu_su']) ? trim($data['tieu_su']) : null]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `tac_gia` SET `ten` = ?, `tieu_su` = ? WHERE `id` = ?";
        return $this->execute($sql, [trim($data['ten']), !empty($data['tieu_su']) ? trim($data['tieu_su']) : null, $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `tac_gia` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Kiểm tra và xóa an toàn kèm ràng buộc toàn vẹn
     */
    public function deleteSafe(int $id): array {
        $author = $this->findById($id);
        if (!$author) {
            return ['success' => false, 'message' => 'Không tìm thấy tác giả cần xóa.'];
        }

        $bookCount = (int)$author['tong_sach'];
        if ($bookCount > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa tác giả '{$author['ten']}' vì đang có {$bookCount} tác phẩm gắn với tác giả này. Vui lòng thay đổi tác giả cho các tựa sách trước."
            ];
        }

        $ok = $this->delete($id);
        return [
            'success' => $ok,
            'message' => $ok ? "Đã xóa tác giả '{$author['ten']}' thành công!" : "Lỗi khi xóa tác giả."
        ];
    }
}
