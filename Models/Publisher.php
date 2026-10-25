<?php
namespace Models;

use Core\Model;

class Publisher extends Model {
    public function getAll(): array {
        $sql = "SELECT nxb.*, COUNT(s.id) AS tong_sach
                FROM `nha_xuat_ban` nxb
                LEFT JOIN `sach` s ON nxb.id = s.nxb_id AND s.dang_ban = 1
                GROUP BY nxb.id
                ORDER BY tong_sach DESC, nxb.ten ASC";
        return $this->fetchAll($sql);
    }

    public function findById(int $id): ?array {
        $sql = "SELECT * FROM `nha_xuat_ban` WHERE `id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO `nha_xuat_ban` (`ten`, `dia_chi`, `lien_he`) VALUES (?, ?, ?)";
        $this->execute($sql, [
            $data['ten'],
            $data['dia_chi'] ?? null,
            $data['lien_he'] ?? null
        ]);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE `nha_xuat_ban` SET `ten` = ?, `dia_chi` = ?, `lien_he` = ? WHERE `id` = ?";
        return $this->execute($sql, [
            $data['ten'],
            $data['dia_chi'] ?? null,
            $data['lien_he'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM `nha_xuat_ban` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }
}
