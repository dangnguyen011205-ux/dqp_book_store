<?php
namespace Models;

use Core\Model;

class Publisher extends Model {
    /**
     * Lấy toàn bộ nhà xuất bản kèm đếm số lượng đầu sách và tìm kiếm
     */
    public function getAll(?string $search = null): array {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where = "(nxb.ten LIKE ? OR nxb.dia_chi LIKE ? OR nxb.lien_he LIKE ?)";
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
            $params[] = '%' . trim($search) . '%';
        }

        $sql = "SELECT nxb.*, COUNT(s.id) AS tong_sach
                FROM `nha_xuat_ban` nxb
                LEFT JOIN `sach` s ON nxb.id = s.nxb_id
                WHERE $where
                GROUP BY nxb.id
                ORDER BY tong_sach DESC, nxb.ten ASC";
        return $this->fetchAll($sql, $params);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT nxb.*, COUNT(s.id) AS tong_sach
                FROM `nha_xuat_ban` nxb
                LEFT JOIN `sach` s ON nxb.id = s.nxb_id
                WHERE nxb.id = ? 
                GROUP BY nxb.id
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `nha_xuat_ban` (`ten`, `dia_chi`, `lien_he`) VALUES (?, ?, ?)";
        $this->execute($sql, [
            trim($data['ten']),
            !empty($data['dia_chi']) ? trim($data['dia_chi']) : null,
            !empty($data['lien_he']) ? trim($data['lien_he']) : null
        ]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `nha_xuat_ban` SET `ten` = ?, `dia_chi` = ?, `lien_he` = ? WHERE `id` = ?";
        return $this->execute($sql, [
            trim($data['ten']),
            !empty($data['dia_chi']) ? trim($data['dia_chi']) : null,
            !empty($data['lien_he']) ? trim($data['lien_he']) : null,
            $id
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `nha_xuat_ban` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Kiểm tra và xóa an toàn kèm ràng buộc toàn vẹn
     */
    public function deleteSafe(int $id): array {
        $pub = $this->findById($id);
        if (!$pub) {
            return ['success' => false, 'message' => 'Không tìm thấy nhà xuất bản cần xóa.'];
        }

        $bookCount = (int)$pub['tong_sach'];
        if ($bookCount > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa Nhà xuất bản '{$pub['ten']}' vì đang có {$bookCount} tựa sách thuộc NXB này. Vui lòng chuyển các tựa sách sang NXB khác trước."
            ];
        }

        $ok = $this->delete($id);
        return [
            'success' => $ok,
            'message' => $ok ? "Đã xóa Nhà xuất bản '{$pub['ten']}' thành công!" : "Lỗi khi xóa nhà xuất bản."
        ];
    }
}
