<?php
namespace Models;

use Core\Model;

class Author extends Model {
    public function getAll(): array {
        $sql = "SELECT tg.*, COUNT(DISTINCT stg.sach_id) AS tong_sach
                FROM `tac_gia` tg
                LEFT JOIN `sach_tac_gia` stg ON tg.id = stg.tac_gia_id
                LEFT JOIN `sach` s ON stg.sach_id = s.id AND s.dang_ban = 1
                GROUP BY tg.id
                ORDER BY tong_sach DESC, tg.ten ASC";
        return $this->fetchAll($sql);
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
        $sql = "SELECT * FROM `tac_gia` WHERE `id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `tac_gia` (`ten`, `tieu_su`) VALUES (?, ?)";
        $this->execute($sql, [$data['ten'], $data['tieu_su'] ?? null]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `tac_gia` SET `ten` = ?, `tieu_su` = ? WHERE `id` = ?";
        return $this->execute($sql, [$data['ten'], $data['tieu_su'] ?? null, $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `tac_gia` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }
}
