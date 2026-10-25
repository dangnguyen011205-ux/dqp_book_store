<?php
namespace Models;

use Core\Model;

class Review extends Model {
    /**
     * Lấy danh sách đánh giá hiển thị của 1 cuốn sách
     */
    public function getByBookId(int $bookId, string $status = 'hien_thi'): array {
        $sql = "SELECT dg.*, nd.ho_ten, nd.email
                FROM `danh_gia` dg
                JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                WHERE dg.sach_id = ?";
        
        $params = [$bookId];
        if (!empty($status)) {
            $sql .= " AND dg.trang_thai = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY dg.ngay_tao DESC";
        return $this->fetchAll($sql, $params);
    }

    /**
     * Thống kê sao trung bình và số lượng từng mức 1-5 sao
     */
    public function getRatingSummary(int $bookId): array {
        $sql = "SELECT so_sao, COUNT(*) AS so_luong
                FROM `danh_gia`
                WHERE sach_id = ? AND trang_thai = 'hien_thi'
                GROUP BY so_sao";
        $rows = $this->fetchAll($sql, [$bookId]);

        $stars = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $totalCount = 0;
        $totalStars = 0;

        foreach ($rows as $r) {
            $s = (int)$r['so_sao'];
            $c = (int)$r['so_luong'];
            if (isset($stars[$s])) {
                $stars[$s] = $c;
                $totalCount += $c;
                $totalStars += ($s * $c);
            }
        }

        $avgRating = $totalCount > 0 ? round($totalStars / $totalCount, 1) : 5.0;

        // Tính tỷ lệ % từng mức sao
        $percentages = [];
        foreach ($stars as $s => $count) {
            $percentages[$s] = $totalCount > 0 ? round(($count / $totalCount) * 100) : 0;
        }

        return [
            'average'     => $avgRating,
            'total'       => $totalCount,
            'stars'       => $stars,
            'percentages' => $percentages
        ];
    }

    /**
     * Kiểm tra người dùng đã đánh giá cuốn sách này chưa
     */
    public function hasUserReviewed(int $userId, int $bookId): bool {
        $sql = "SELECT id FROM `danh_gia` WHERE `nguoi_dung_id` = ? AND `sach_id` = ? LIMIT 1";
        return $this->fetchOne($sql, [$userId, $bookId]) !== null;
    }

    /**
     * Kiểm tra người dùng đã từng mua cuốn sách này trong đơn hàng hoàn thành chưa
     */
    public function hasUserPurchased(int $userId, int $bookId): bool {
        $sql = "SELECT ctdh.id 
                FROM `chi_tiet_don_hang` ctdh
                JOIN `don_hang` dh ON ctdh.don_hang_id = dh.id
                WHERE dh.nguoi_dung_id = ? AND ctdh.sach_id = ? AND dh.trang_thai = 'hoan_thanh'
                LIMIT 1";
        return $this->fetchOne($sql, [$userId, $bookId]) !== null;
    }

    /**
     * Tạo đánh giá mới (mặc định trạng thái cho_duyet)
     */
    public function create(array $data): int {
        $sql = "INSERT INTO `danh_gia` (`nguoi_dung_id`, `sach_id`, `so_sao`, `noi_dung`, `trang_thai`, `ngay_tao`)
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $this->execute($sql, [
            $data['nguoi_dung_id'],
            $data['sach_id'],
            $data['so_sao'],
            $data['noi_dung'] ?? null,
            $data['trang_thai'] ?? 'cho_duyet'
        ]);

        return $this->lastInsertId();
    }

    /**
     * Lấy các đánh giá tiêu biểu hiển thị ngoài trang chủ
     */
    public function getFeaturedReviews(int $limit = 3): array {
        $sql = "SELECT dg.*, nd.ho_ten, s.tieu_de AS sach_tieu_de, s.anh_bia AS sach_anh_bia
                FROM `danh_gia` dg
                JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                JOIN `sach` s ON dg.sach_id = s.id
                WHERE dg.trang_thai = 'hien_thi' AND dg.so_sao >= 4
                ORDER BY dg.id DESC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Admin duyệt đánh giá
     */
    public function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE `danh_gia` SET `trang_thai` = ? WHERE `id` = ?";
        return $this->execute($sql, [$status, $id]);
    }
}
