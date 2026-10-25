<?php
namespace Models;

use Core\Model;

class Book extends Model {
    /**
     * Lấy danh sách sách đang bán kèm thông tin thể loại, NXB, tác giả và đánh giá
     */
    public function getBooks(int $limit = 10, int $offset = 0): array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 5.0) AS diem_sao,
                       COUNT(DISTINCT dg.id) AS so_danh_gia
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                WHERE s.dang_ban = 1
                GROUP BY s.id
                ORDER BY s.id DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sách bán chạy theo thể loại hoặc tất cả
     */
    public function getBestsellers(int $limit = 5, ?int $theLoaiId = null): array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 4.8) AS diem_sao,
                       COUNT(DISTINCT dg.id) AS so_danh_gia,
                       COALESCE(SUM(ctdh.so_luong), 0) AS da_ban
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                LEFT JOIN `chi_tiet_don_hang` ctdh ON s.id = ctdh.sach_id
                WHERE s.dang_ban = 1";

        $params = [];
        if ($theLoaiId !== null && $theLoaiId > 0) {
            $sql .= " AND s.the_loai_id = ?";
            $params[] = $theLoaiId;
        }

        $sql .= " GROUP BY s.id ORDER BY da_ban DESC, s.id DESC LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p, \PDO::PARAM_INT);
        }
        $stmt->bindValue($idx, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sách Flash Deals
     */
    public function getFlashDeals(int $limit = 4): array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 4.9) AS diem_sao,
                       COUNT(DISTINCT dg.id) AS so_danh_gia
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                WHERE s.dang_ban = 1
                GROUP BY s.id
                ORDER BY s.gia ASC, s.id ASC
                LIMIT ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy chi tiết sách theo ID
     */
    public function getDetail(int $id): ?array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten, nxb.dia_chi AS nxb_dia_chi,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 5.0) AS diem_sao,
                       COUNT(DISTINCT dg.id) AS so_danh_gia,
                       COALESCE(SUM(ctdh.so_luong), 0) AS da_ban
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                LEFT JOIN `chi_tiet_don_hang` ctdh ON s.id = ctdh.sach_id
                WHERE s.id = ? AND s.dang_ban = 1
                GROUP BY s.id
                LIMIT 1";
        
        $book = $this->fetchOne($sql, [$id]);
        if (!$book) {
            return null;
        }

        // Lấy danh sách các tác giả chi tiết
        $sqlAuthors = "SELECT tg.* FROM `tac_gia` tg
                       JOIN `sach_tac_gia` stg ON tg.id = stg.tac_gia_id
                       WHERE stg.sach_id = ?";
        $book['tac_gia_list'] = $this->fetchAll($sqlAuthors, [$id]);

        return $book;
    }

    /**
     * Lấy sách cùng thể loại (liên quan)
     */
    public function getRelatedBooks(int $bookId, ?int $theLoaiId, int $limit = 5): array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 4.8) AS diem_sao
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                WHERE s.id != ? AND s.dang_ban = 1";
        
        $params = [$bookId];
        if ($theLoaiId !== null) {
            $sql .= " AND s.the_loai_id = ?";
            $params[] = $theLoaiId;
        }

        $sql .= " GROUP BY s.id ORDER BY RAND() LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p, \PDO::PARAM_INT);
        }
        $stmt->bindValue($idx, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm và lọc sách đa tiêu chí kết hợp phân trang
     */
    public function filterBooks(array $filters, int $page = 1, int $perPage = 12): array {
        $where = ["s.dang_ban = 1"];
        $params = [];

        // 1. Từ khóa
        if (!empty($filters['q'])) {
            $q = '%' . trim($filters['q']) . '%';
            $where[] = "(s.tieu_de LIKE ? OR tg.ten LIKE ? OR s.mo_ta LIKE ?)";
            $params[] = $q;
            $params[] = $q;
            $params[] = $q;
        }

        // 2. Thể loại
        if (!empty($filters['the_loai'])) {
            if (is_array($filters['the_loai'])) {
                $placeholders = implode(',', array_fill(0, count($filters['the_loai']), '?'));
                $where[] = "s.the_loai_id IN ($placeholders)";
                foreach ($filters['the_loai'] as $tlId) {
                    $params[] = (int)$tlId;
                }
            } else {
                $where[] = "s.the_loai_id = ?";
                $params[] = (int)$filters['the_loai'];
            }
        }

        // 3. Tác giả
        if (!empty($filters['tac_gia'])) {
            if (is_array($filters['tac_gia'])) {
                $placeholders = implode(',', array_fill(0, count($filters['tac_gia']), '?'));
                $where[] = "stg.tac_gia_id IN ($placeholders)";
                foreach ($filters['tac_gia'] as $tgId) {
                    $params[] = (int)$tgId;
                }
            } else {
                $where[] = "stg.tac_gia_id = ?";
                $params[] = (int)$filters['tac_gia'];
            }
        }

        // 4. Nhà xuất bản
        if (!empty($filters['nxb'])) {
            if (is_array($filters['nxb'])) {
                $placeholders = implode(',', array_fill(0, count($filters['nxb']), '?'));
                $where[] = "s.nxb_id IN ($placeholders)";
                foreach ($filters['nxb'] as $nxbId) {
                    $params[] = (int)$nxbId;
                }
            } else {
                $where[] = "s.nxb_id = ?";
                $params[] = (int)$filters['nxb'];
            }
        }

        // 5. Khoảng giá
        if (isset($filters['gia_min']) && is_numeric($filters['gia_min'])) {
            $where[] = "s.gia >= ?";
            $params[] = (float)$filters['gia_min'];
        }
        if (isset($filters['gia_max']) && is_numeric($filters['gia_max'])) {
            $where[] = "s.gia <= ?";
            $params[] = (float)$filters['gia_max'];
        }

        // 6. Tình trạng còn hàng
        if (!empty($filters['con_hang'])) {
            $where[] = "s.so_luong_ton > 0";
        }

        $whereClause = implode(" AND ", $where);

        // HAVING filter cho số sao nếu có
        $havingClause = "";
        $havingParams = [];
        if (!empty($filters['so_sao']) && is_numeric($filters['so_sao'])) {
            $havingClause = " HAVING diem_sao >= ?";
            $havingParams[] = (float)$filters['so_sao'];
        }

        // Đếm tổng số bản ghi
        if (!empty($havingClause)) {
            $countSql = "SELECT COUNT(*) FROM (
                SELECT s.id, COALESCE(AVG(dg.so_sao), 5.0) AS diem_sao
                FROM `sach` s
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                WHERE $whereClause
                GROUP BY s.id
                $havingClause
            ) AS count_sub";
            $totalStmt = $this->db->prepare($countSql);
            $totalStmt->execute(array_merge($params, $havingParams));
        } else {
            $countSql = "SELECT COUNT(DISTINCT s.id) AS total
                         FROM `sach` s
                         LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                         LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                         WHERE $whereClause";
            $totalStmt = $this->db->prepare($countSql);
            $totalStmt->execute($params);
        }
        $total = (int)($totalStmt->fetchColumn() ?: 0);

        // Sắp xếp
        $sortOrder = "s.id DESC";
        $sort = $filters['sort'] ?? '';
        switch ($sort) {
            case 'gia_tang':
                $sortOrder = "s.gia ASC";
                break;
            case 'gia_giam':
                $sortOrder = "s.gia DESC";
                break;
            case 'ban_chay':
                $sortOrder = "da_ban DESC, s.id DESC";
                break;
            case 'danh_gia':
                $sortOrder = "diem_sao DESC, s.id DESC";
                break;
            case 'moi_nhat':
            default:
                $sortOrder = "s.id DESC";
                break;
        }

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       COALESCE(AVG(dg.so_sao), 5.0) AS diem_sao,
                       COUNT(DISTINCT dg.id) AS so_danh_gia,
                       COALESCE(SUM(ctdh.so_luong), 0) AS da_ban
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                LEFT JOIN `danh_gia` dg ON s.id = dg.sach_id AND dg.trang_thai = 'hien_thi'
                LEFT JOIN `chi_tiet_don_hang` ctdh ON s.id = ctdh.sach_id
                WHERE $whereClause
                GROUP BY s.id
                $havingClause
                ORDER BY $sortOrder
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        foreach ($havingParams as $hp) {
            $stmt->bindValue($idx++, $hp);
        }
        $stmt->bindValue($idx++, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue($idx++, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $books = $stmt->fetchAll();

        return [
            'data'       => $books,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => max(1, (int)ceil($total / $perPage))
        ];
    }
}
