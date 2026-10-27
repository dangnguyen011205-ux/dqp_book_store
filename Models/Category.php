<?php
namespace Models;

use Core\Model;

class Category extends Model {
    /**
     * Lấy danh sách thể loại kèm đếm số lượng sách đang kinh doanh
     */
    public function getAll(?string $search = null): array {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where = "(t.ten LIKE ? OR t.mo_ta LIKE ?)";
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
        }

        $sql = "SELECT t.*, 
                       COUNT(s.id) AS tong_sach,
                       COALESCE(SUM(s.so_luong_ton), 0) AS tong_ton_kho
                FROM `the_loai` t 
                LEFT JOIN `sach` s ON t.id = s.the_loai_id
                WHERE $where
                GROUP BY t.id 
                ORDER BY t.id ASC";
        return $this->fetchAll($sql, $params);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT t.*, COUNT(s.id) AS tong_sach
                FROM `the_loai` t 
                LEFT JOIN `sach` s ON t.id = s.the_loai_id
                WHERE t.id = ? 
                GROUP BY t.id
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `the_loai` (`ten`, `mo_ta`) VALUES (?, ?)";
        $this->execute($sql, [trim($data['ten']), !empty($data['mo_ta']) ? trim($data['mo_ta']) : null]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `the_loai` SET `ten` = ?, `mo_ta` = ? WHERE `id` = ?";
        return $this->execute($sql, [trim($data['ten']), !empty($data['mo_ta']) ? trim($data['mo_ta']) : null, $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `the_loai` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Kiểm tra và xóa an toàn kèm ràng buộc toàn vẹn
     */
    public function deleteSafe(int $id): array {
        $cat = $this->findById($id);
        if (!$cat) {
            return ['success' => false, 'message' => 'Không tìm thấy thể loại cần xóa.'];
        }

        $bookCount = (int)$cat['tong_sach'];
        if ($bookCount > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa thể loại '{$cat['ten']}' vì đang có {$bookCount} tựa sách thuộc thể loại này. Vui lòng chuyển các tựa sách sang thể loại khác trước."
            ];
        }

        $ok = $this->delete($id);
        return [
            'success' => $ok,
            'message' => $ok ? "Đã xóa thể loại '{$cat['ten']}' thành công!" : "Lỗi khi xóa thể loại."
        ];
    }
}
