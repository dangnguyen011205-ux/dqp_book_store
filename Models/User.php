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
        $sql = "SELECT u.id, u.ho_ten, u.email, u.so_dien_thoai, u.vai_tro, u.trang_thai, u.ngay_tao,
                       COUNT(DISTINCT d.id) AS so_don_hang,
                       COALESCE(SUM(CASE WHEN d.trang_thai = 'hoan_thanh' THEN d.tong_thanh_toan ELSE 0 END), 0) AS tong_chi_tieu
                FROM `nguoi_dung` u
                LEFT JOIN `don_hang` d ON u.id = d.nguoi_dung_id
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (u.`ho_ten` LIKE ? OR u.`email` LIKE ? OR u.`so_dien_thoai` LIKE ?)";
            $k = '%' . $keyword . '%';
            $params[] = $k;
            $params[] = $k;
            $params[] = $k;
        }

        if (!empty($role)) {
            $sql .= " AND u.`vai_tro` = ?";
            $params[] = $role;
        }

        if (!empty($status)) {
            $sql .= " AND u.`trang_thai` = ?";
            $params[] = $status;
        }

        $sql .= " GROUP BY u.id ORDER BY u.`id` DESC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Thống kê tổng hợp số lượng tài khoản theo vai trò và trạng thái
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN `vai_tro` = 'khach_hang' THEN 1 ELSE 0 END) AS khach_hang,
                    SUM(CASE WHEN `vai_tro` = 'quan_tri' THEN 1 ELSE 0 END) AS quan_tri,
                    SUM(CASE WHEN `trang_thai` = 'hoat_dong' THEN 1 ELSE 0 END) AS hoat_dong,
                    SUM(CASE WHEN `trang_thai` = 'khoa' THEN 1 ELSE 0 END) AS khoa
                FROM `nguoi_dung`";
        $stats = $this->fetchOne($sql);
        return [
            'total'      => (int)($stats['total'] ?? 0),
            'khach_hang' => (int)($stats['khach_hang'] ?? 0),
            'quan_tri'   => (int)($stats['quan_tri'] ?? 0),
            'hoat_dong'  => (int)($stats['hoat_dong'] ?? 0),
            'khoa'       => (int)($stats['khoa'] ?? 0)
        ];
    }

    /**
     * Lấy thông tin chi tiết người dùng kèm danh sách địa chỉ và các đơn hàng gần nhất
     */
    public function getUserDetailWithOrders(int $id): ?array {
        $user = $this->findById($id);
        if (!$user) {
            return null;
        }
        unset($user['mat_khau_hash']);

        // Danh sách địa chỉ nhận hàng
        $sqlAddresses = "SELECT * FROM `dia_chi` WHERE `nguoi_dung_id` = ? ORDER BY `mac_dinh` DESC, `id` DESC";
        $user['dia_chi_list'] = $this->fetchAll($sqlAddresses, [$id]);

        // 5 đơn hàng gần nhất
        $sqlOrders = "SELECT id, tong_thanh_toan, trang_thai, ngay_dat 
                      FROM `don_hang` 
                      WHERE `nguoi_dung_id` = ? 
                      ORDER BY `id` DESC LIMIT 5";
        $rawOrders = $this->fetchAll($sqlOrders, [$id]);
        foreach ($rawOrders as &$ord) {
            $ord['ma_don_hang'] = 'BK-' . str_pad((string)$ord['id'], 6, '0', STR_PAD_LEFT);
            $ord['tong_tien'] = (float)$ord['tong_thanh_toan'];
            $ord['ngay_tao'] = date('d/m/Y H:i', strtotime($ord['ngay_dat']));
        }
        unset($ord);
        $user['don_hang_list'] = $rawOrders;

        // Thống kê tổng quan đơn hàng & chi tiêu
        $sqlStats = "SELECT COUNT(*) AS tong_don,
                            COALESCE(SUM(CASE WHEN `trang_thai` = 'hoan_thanh' THEN `tong_thanh_toan` ELSE 0 END), 0) AS tong_chi_tieu
                     FROM `don_hang` WHERE `nguoi_dung_id` = ?";
        $stats = $this->fetchOne($sqlStats, [$id]);
        $user['tong_don'] = (int)($stats['tong_don'] ?? 0);
        $user['tong_chi_tieu'] = (float)($stats['tong_chi_tieu'] ?? 0);

        return $user;
    }
}
