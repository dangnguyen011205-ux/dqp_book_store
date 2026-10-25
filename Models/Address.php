<?php
namespace Models;

use Core\Model;

class Address extends Model {
    public function getByUserId(int $userId): array {
        $sql = "SELECT * FROM `dia_chi` WHERE `nguoi_dung_id` = ? ORDER BY `mac_dinh` DESC, `id` DESC";
        return $this->fetchAll($sql, [$userId]);
    }

    public function getById(int $id, int $userId): ?array {
        $sql = "SELECT * FROM `dia_chi` WHERE `id` = ? AND `nguoi_dung_id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$id, $userId]);
    }

    public function findById(int $id, int $userId): ?array {
        return $this->getById($id, $userId);
    }

    public function getDefault(int $userId): ?array {
        $sql = "SELECT * FROM `dia_chi` WHERE `nguoi_dung_id` = ? AND `mac_dinh` = 1 LIMIT 1";
        $address = $this->fetchOne($sql, [$userId]);
        if (!$address) {
            $sql = "SELECT * FROM `dia_chi` WHERE `nguoi_dung_id` = ? ORDER BY `id` ASC LIMIT 1";
            $address = $this->fetchOne($sql, [$userId]);
        }
        return $address;
    }

    public function create(array $data): int {
        $this->beginTransaction();
        try {
            // Nếu địa chỉ này đặt là mặc định hoặc là địa chỉ đầu tiên của user
            $countSql = "SELECT COUNT(*) as total FROM `dia_chi` WHERE `nguoi_dung_id` = ?";
            $row = $this->fetchOne($countSql, [$data['nguoi_dung_id']]);
            $isFirst = ($row['total'] ?? 0) == 0;
            $isDefault = ($data['mac_dinh'] ?? 0) || $isFirst ? 1 : 0;

            if ($isDefault) {
                $this->execute("UPDATE `dia_chi` SET `mac_dinh` = 0 WHERE `nguoi_dung_id` = ?", [$data['nguoi_dung_id']]);
            }

            $sql = "INSERT INTO `dia_chi` (`nguoi_dung_id`, `nguoi_nhan`, `so_dien_thoai`, `dia_chi`, `mac_dinh`) 
                    VALUES (?, ?, ?, ?, ?)";
            $this->execute($sql, [
                $data['nguoi_dung_id'],
                $data['nguoi_nhan'],
                $data['so_dien_thoai'],
                $data['dia_chi'],
                $isDefault
            ]);

            $id = $this->lastInsertId();
            $this->commit();
            return $id;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function update(int $id, int $userId, array $data): bool {
        $this->beginTransaction();
        try {
            if (!empty($data['mac_dinh'])) {
                $this->execute("UPDATE `dia_chi` SET `mac_dinh` = 0 WHERE `nguoi_dung_id` = ?", [$userId]);
            }

            $sql = "UPDATE `dia_chi` SET `nguoi_nhan` = ?, `so_dien_thoai` = ?, `dia_chi` = ?, `mac_dinh` = ? 
                    WHERE `id` = ? AND `nguoi_dung_id` = ?";
            
            $res = $this->execute($sql, [
                $data['nguoi_nhan'],
                $data['so_dien_thoai'],
                $data['dia_chi'],
                $data['mac_dinh'] ? 1 : 0,
                $id,
                $userId
            ]);

            $this->commit();
            return $res;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function delete(int $id, int $userId): bool {
        $this->beginTransaction();
        try {
            $target = $this->getById($id, $userId);
            if (!$target) {
                $this->rollBack();
                return false;
            }

            $res = $this->execute("DELETE FROM `dia_chi` WHERE `id` = ? AND `nguoi_dung_id` = ?", [$id, $userId]);

            // Nếu xóa trúng địa chỉ mặc định, tự động gán địa chỉ còn lại gần nhất làm mặc định
            if ($target['mac_dinh'] == 1) {
                $remaining = $this->fetchOne("SELECT id FROM `dia_chi` WHERE `nguoi_dung_id` = ? ORDER BY id DESC LIMIT 1", [$userId]);
                if ($remaining) {
                    $this->execute("UPDATE `dia_chi` SET `mac_dinh` = 1 WHERE `id` = ?", [$remaining['id']]);
                }
            }

            $this->commit();
            return $res;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function setDefault(int $id, int $userId): bool {
        $this->beginTransaction();
        try {
            $this->execute("UPDATE `dia_chi` SET `mac_dinh` = 0 WHERE `nguoi_dung_id` = ?", [$userId]);
            $res = $this->execute("UPDATE `dia_chi` SET `mac_dinh` = 1 WHERE `id` = ? AND `nguoi_dung_id` = ?", [$id, $userId]);
            $this->commit();
            return $res;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }
}
