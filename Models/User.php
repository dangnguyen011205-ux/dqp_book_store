<?php
namespace Models;

use Core\Model;

class User extends Model {
    public function findByEmail(string $email): ?array {
        $sql = "SELECT * FROM `nguoi_dung` WHERE `email` = ? LIMIT 1";
        return $this->fetchOne($sql, [$email]);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM `nguoi_dung` WHERE `id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `nguoi_dung` (`ho_ten`, `email`, `mat_khau_hash`, `so_dien_thoai`, `vai_tro`, `trang_thai`, `ngay_tao`) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $this->execute($sql, [
            $data['ho_ten'],
            $data['email'],
            $data['mat_khau_hash'],
            $data['so_dien_thoai'] ?? null,
            $data['vai_tro'] ?? 'khach_hang',
            $data['trang_thai'] ?? 'hoat_dong'
        ]);

        return $this->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool {
        $sql = "UPDATE `nguoi_dung` SET `ho_ten` = ?, `so_dien_thoai` = ? WHERE `id` = ?";
        return $this->execute($sql, [
            $data['ho_ten'],
            $data['so_dien_thoai'] ?? null,
            $id
        ]);
    }

    public function updatePassword(int $id, string $newPasswordHash): bool {
        $sql = "UPDATE `nguoi_dung` SET `mat_khau_hash` = ? WHERE `id` = ?";
        return $this->execute($sql, [$newPasswordHash, $id]);
    }

    public function isLocked(int $id): bool {
        $user = $this->findById($id);
        return $user && $user['trang_thai'] === 'khoa';
    }

    public function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE `nguoi_dung` SET `trang_thai` = ? WHERE `id` = ?";
        return $this->execute($sql, [$status, $id]);
    }

    public function changeRole(int $id, string $role): bool {
        $sql = "UPDATE `nguoi_dung` SET `vai_tro` = ? WHERE `id` = ?";
        return $this->execute($sql, [$role, $id]);
    }

    public function getAll(string $keyword = '', string $role = '', string $status = ''): array {
        $sql = "SELECT id, ho_ten, email, so_dien_thoai, vai_tro, trang_thai, ngay_tao FROM `nguoi_dung` WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (`ho_ten` LIKE ? OR `email` LIKE ? OR `so_dien_thoai` LIKE ?)";
            $k = '%' . $keyword . '%';
            $params[] = $k;
            $params[] = $k;
            $params[] = $k;
        }

        if (!empty($role)) {
            $sql .= " AND `vai_tro` = ?";
            $params[] = $role;
        }

        if (!empty($status)) {
            $sql .= " AND `trang_thai` = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY `id` DESC";
        return $this->fetchAll($sql, $params);
    }
}
