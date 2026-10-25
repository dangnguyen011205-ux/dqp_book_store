<?php
namespace Models;

use Core\Model;

class Category extends Model {
    public function getAll(): array {
        $sql = "SELECT t.*, COUNT(s.id) as tong_sach 
                FROM `the_loai` t 
                LEFT JOIN `sach` s ON t.id = s.the_loai_id AND s.dang_ban = 1
                GROUP BY t.id 
                ORDER BY t.id ASC";
        return $this->fetchAll($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM `the_loai` WHERE `id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `the_loai` (`ten`, `mo_ta`) VALUES (?, ?)";
        $this->execute($sql, [$data['ten'], $data['mo_ta'] ?? null]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `the_loai` SET `ten` = ?, `mo_ta` = ? WHERE `id` = ?";
        return $this->execute($sql, [$data['ten'], $data['mo_ta'] ?? null, $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `the_loai` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }
}
