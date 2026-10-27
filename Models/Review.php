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
     * Admin duyệt / ẩn đánh giá
     */
    public function updateStatus(int $id, string $status): bool {
        $validStatuses = ['cho_duyet', 'hien_thi', 'an'];
        if (!in_array($status, $validStatuses, true)) {
            return false;
        }
        $sql = "UPDATE `danh_gia` SET `trang_thai` = ? WHERE `id` = ?";
        return $this->execute($sql, [$status, $id]);
    }

    /**
     * Cập nhật trạng thái đồng loạt cho nhiều đánh giá (Bulk moderation)
     */
    public function bulkUpdateStatus(array $ids, string $status): int {
        $validStatuses = ['cho_duyet', 'hien_thi', 'an'];
        if (!in_array($status, $validStatuses, true) || empty($ids)) {
            return 0;
        }

        $cleanIds = array_filter(array_map('intval', $ids), fn($id) => $id > 0);
        if (empty($cleanIds)) {
            return 0;
        }

        $placeholders = implode(',', array_fill(0, count($cleanIds), '?'));
        $sql = "UPDATE `danh_gia` SET `trang_thai` = ? WHERE `id` IN ($placeholders)";
        $params = array_merge([$status], $cleanIds);

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Xóa đánh giá vi phạm / spam
     */
    public function deleteReview(int $id): bool {
        $sql = "DELETE FROM `danh_gia` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Thống kê KPI tổng quan cho phân hệ Quản lý & Duyệt Đánh Giá
     */
    public function getAdminStats(): array {
        // 1. Số lượng theo trạng thái
        $countsSql = "SELECT 
                        COUNT(*) AS total_reviews,
                        COALESCE(SUM(CASE WHEN trang_thai = 'cho_duyet' THEN 1 ELSE 0 END), 0) AS pending_reviews,
                        COALESCE(SUM(CASE WHEN trang_thai = 'hien_thi' THEN 1 ELSE 0 END), 0) AS approved_reviews,
                        COALESCE(SUM(CASE WHEN trang_thai = 'an' THEN 1 ELSE 0 END), 0) AS hidden_reviews,
                        COALESCE(AVG(CASE WHEN trang_thai = 'hien_thi' THEN so_sao ELSE NULL END), 0) AS avg_rating,
                        COALESCE(SUM(CASE WHEN trang_thai = 'hien_thi' AND so_sao = 5 THEN 1 ELSE 0 END), 0) AS five_star_count,
                        COALESCE(SUM(CASE WHEN trang_thai = 'hien_thi' AND so_sao >= 4 THEN 1 ELSE 0 END), 0) AS positive_count
                      FROM `danh_gia`";
        $row = $this->db->query($countsSql)->fetch() ?: [];

        $total = (int)($row['total_reviews'] ?? 0);
        $pending = (int)($row['pending_reviews'] ?? 0);
        $approved = (int)($row['approved_reviews'] ?? 0);
        $hidden = (int)($row['hidden_reviews'] ?? 0);
        $avgRating = round((float)($row['avg_rating'] ?? 5.0), 1);
        if ($approved === 0) {
            $avgRating = 5.0;
        }

        $fiveStarCount = (int)($row['five_star_count'] ?? 0);
        $fiveStarPct = $approved > 0 ? round(($fiveStarCount / $approved) * 100, 1) : 100.0;

        $positiveCount = (int)($row['positive_count'] ?? 0);
        $satisfactionRate = $approved > 0 ? round(($positiveCount / $approved) * 100, 1) : 100.0;

        return [
            'total_reviews'     => $total,
            'pending_reviews'   => $pending,
            'approved_reviews'  => $approved,
            'hidden_reviews'    => $hidden,
            'avg_rating'        => $avgRating,
            'five_star_pct'     => $fiveStarPct,
            'satisfaction_rate' => $satisfactionRate
        ];
    }

    /**
     * Thống kê số lượng đánh giá theo từng trạng thái và từng mức sao phục vụ Tabs & Filters
     */
    public function getAdminCounts(): array {
        // Thống kê trạng thái
        $statusSql = "SELECT trang_thai, COUNT(*) AS so_luong FROM `danh_gia` GROUP BY trang_thai";
        $statusRows = $this->fetchAll($statusSql);

        $counts = [
            'tat_ca'    => 0,
            'cho_duyet' => 0,
            'hien_thi'  => 0,
            'an'        => 0
        ];

        foreach ($statusRows as $r) {
            $st = $r['trang_thai'];
            $c = (int)$r['so_luong'];
            if (isset($counts[$st])) {
                $counts[$st] = $c;
            }
            $counts['tat_ca'] += $c;
        }

        // Thống kê số sao
        $starsSql = "SELECT so_sao, COUNT(*) AS so_luong FROM `danh_gia` GROUP BY so_sao";
        $starRows = $this->fetchAll($starsSql);

        $starCounts = [
            'all' => $counts['tat_ca'],
            5     => 0,
            4     => 0,
            3     => 0,
            2     => 0,
            1     => 0
        ];

        foreach ($starRows as $r) {
            $s = (int)$r['so_sao'];
            if (isset($starCounts[$s])) {
                $starCounts[$s] = (int)$r['so_luong'];
            }
        }

        return [
            'status' => $counts,
            'stars'  => $starCounts
        ];
    }

    /**
     * Lấy danh sách đánh giá cho Quản trị viên kèm bộ lọc đa tiêu chí và phân trang
     */
    public function getAdminReviews(array $filters = [], int $page = 1, int $perPage = 10): array {
        $where = ["1=1"];
        $params = [];

        // 1. Lọc theo trạng thái tab
        if (!empty($filters['status']) && $filters['status'] !== 'tat_ca') {
            $where[] = "dg.trang_thai = ?";
            $params[] = $filters['status'];
        }

        // 2. Lọc theo số sao
        if (!empty($filters['star']) && is_numeric($filters['star']) && (int)$filters['star'] >= 1 && (int)$filters['star'] <= 5) {
            $where[] = "dg.so_sao = ?";
            $params[] = (int)$filters['star'];
        }

        // 3. Tìm kiếm theo từ khóa (tên khách, email, tựa sách, nội dung)
        if (!empty($filters['search'])) {
            $keyword = trim($filters['search']);
            $like = '%' . $keyword . '%';
            $where[] = "(nd.ho_ten LIKE ? OR nd.email LIKE ? OR s.tieu_de LIKE ? OR dg.noi_dung LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        // 4. Lọc theo sách cụ thể
        if (!empty($filters['book_id']) && (int)$filters['book_id'] > 0) {
            $where[] = "dg.sach_id = ?";
            $params[] = (int)$filters['book_id'];
        }

        $whereClause = implode(" AND ", $where);

        // Đếm tổng số bản ghi thỏa điều kiện
        $countSql = "SELECT COUNT(*) AS total
                     FROM `danh_gia` dg
                     JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                     JOIN `sach` s ON dg.sach_id = s.id
                     WHERE $whereClause";
        $total = (int)($this->fetchOne($countSql, $params)['total'] ?? 0);

        // Xử lý sắp xếp
        $sort = $filters['sort'] ?? 'moi_nhat';
        $orderBy = match ($sort) {
            'cu_nhat'   => 'dg.ngay_tao ASC',
            'sao_cao'   => 'dg.so_sao DESC, dg.ngay_tao DESC',
            'sao_thap'  => 'dg.so_sao ASC, dg.ngay_tao DESC',
            default     => 'dg.ngay_tao DESC'
        };

        // Phân trang
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT dg.*, 
                       nd.ho_ten AS nguoi_danh_gia_ten, nd.email AS nguoi_danh_gia_email, nd.so_dien_thoai AS nguoi_danh_gia_sdt,
                       s.tieu_de AS sach_tieu_de, s.anh_bia AS sach_anh_bia, s.gia AS sach_gia,
                       tl.ten AS the_loai_ten,
                       (SELECT dh.id 
                        FROM `chi_tiet_don_hang` ctdh 
                        JOIN `don_hang` dh ON ctdh.don_hang_id = dh.id 
                        WHERE dh.nguoi_dung_id = dg.nguoi_dung_id AND ctdh.sach_id = dg.sach_id 
                        ORDER BY dh.id DESC LIMIT 1) AS don_hang_id
                FROM `danh_gia` dg
                JOIN `nguoi_dung` nd ON dg.nguoi_dung_id = nd.id
                JOIN `sach` s ON dg.sach_id = s.id
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                WHERE $whereClause
                ORDER BY $orderBy
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        $stmt->bindValue($idx++, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue($idx++, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $reviews = $stmt->fetchAll();

        return [
            'data'       => $reviews,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => max(1, (int)ceil($total / $perPage))
        ];
    }
}

