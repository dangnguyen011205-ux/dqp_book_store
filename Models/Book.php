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

    /**
     * Thống kê KPI phục vụ trang Quản lý sách & Tồn kho
     */
    public function getAdminStats(): array {
        $sql = "SELECT 
                    COUNT(*) AS total_titles,
                    COALESCE(SUM(CASE WHEN dang_ban = 1 THEN 1 ELSE 0 END), 0) AS active_titles,
                    COALESCE(SUM(CASE WHEN dang_ban = 0 THEN 1 ELSE 0 END), 0) AS inactive_titles,
                    COALESCE(SUM(so_luong_ton), 0) AS total_stock,
                    COALESCE(SUM(CASE WHEN so_luong_ton <= 10 AND dang_ban = 1 THEN 1 ELSE 0 END), 0) AS low_stock_count,
                    COALESCE(SUM(gia * so_luong_ton), 0) AS estimated_stock_value
                FROM `sach`";
        $row = $this->db->query($sql)->fetch();
        return [
            'total_titles'          => (int)($row['total_titles'] ?? 0),
            'active_titles'         => (int)($row['active_titles'] ?? 0),
            'inactive_titles'       => (int)($row['inactive_titles'] ?? 0),
            'total_stock'           => (int)($row['total_stock'] ?? 0),
            'low_stock_count'       => (int)($row['low_stock_count'] ?? 0),
            'estimated_stock_value' => (float)($row['estimated_stock_value'] ?? 0)
        ];
    }

    /**
     * Lấy danh sách sách quản trị kèm bộ lọc đa tiêu chí và phân trang
     */
    public function getAdminBooks(array $filters = [], int $page = 1, int $perPage = 10): array {
        $where = ["1=1"];
        $params = [];

        // Tìm kiếm theo từ khóa
        if (!empty($filters['search'])) {
            $keyword = '%' . trim($filters['search']) . '%';
            $where[] = "(s.tieu_de LIKE ? OR s.id = ? OR tg.ten LIKE ? OR nxb.ten LIKE ?)";
            $idSearch = is_numeric($filters['search']) ? (int)$filters['search'] : -1;
            $params[] = $keyword;
            $params[] = $idSearch;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        // Lọc theo thể loại
        if (!empty($filters['category_id']) && (int)$filters['category_id'] > 0) {
            $where[] = "s.the_loai_id = ?";
            $params[] = (int)$filters['category_id'];
        }

        // Lọc theo trạng thái
        if (!empty($filters['status'])) {
            switch ($filters['status']) {
                case 'dang_ban':
                    $where[] = "s.dang_ban = 1";
                    break;
                case 'sap_het':
                    $where[] = "s.so_luong_ton <= 10";
                    break;
                case 'ngung_ban':
                    $where[] = "s.dang_ban = 0";
                    break;
            }
        }

        $whereClause = implode(" AND ", $where);

        // Đếm tổng số
        $countSql = "SELECT COUNT(DISTINCT s.id) AS total
                     FROM `sach` s
                     LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                     LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                     LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                     LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                     WHERE $whereClause";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)($countStmt->fetchColumn() ?: 0);

        // Phân trang
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT s.*, 
                       tl.ten AS the_loai_ten,
                       nxb.ten AS nxb_ten,
                       GROUP_CONCAT(DISTINCT tg.ten SEPARATOR ', ') AS tac_gia_ten,
                       GROUP_CONCAT(DISTINCT tg.id SEPARATOR ',') AS tac_gia_ids
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                LEFT JOIN `sach_tac_gia` stg ON s.id = stg.sach_id
                LEFT JOIN `tac_gia` tg ON stg.tac_gia_id = tg.id
                WHERE $whereClause
                GROUP BY s.id
                ORDER BY s.id DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
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

    /**
     * Lấy dữ liệu chi tiết sách để hiển thị lên Modal chỉnh sửa
     */
    public function getBookForEdit(int $id): ?array {
        $sql = "SELECT s.*, tl.ten AS the_loai_ten, nxb.ten AS nxb_ten
                FROM `sach` s
                LEFT JOIN `the_loai` tl ON s.the_loai_id = tl.id
                LEFT JOIN `nha_xuat_ban` nxb ON s.nxb_id = nxb.id
                WHERE s.id = ? LIMIT 1";
        $book = $this->fetchOne($sql, [$id]);
        if (!$book) {
            return null;
        }

        // Lấy danh sách ID tác giả
        $authorSql = "SELECT tac_gia_id FROM `sach_tac_gia` WHERE sach_id = ?";
        $stmt = $this->db->prepare($authorSql);
        $stmt->execute([$id]);
        $book['author_ids'] = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $book;
    }

    /**
     * Thêm sách mới kèm liên kết tác giả và phiếu nhập kho khởi tạo
     */
    public function createBook(array $data, array $authorIds = []): int {
        try {
            $this->beginTransaction();

            $sql = "INSERT INTO `sach` (`tieu_de`, `gia`, `mo_ta`, `anh_bia`, `so_trang`, `nam_xuat_ban`, `so_luong_ton`, `the_loai_id`, `nxb_id`, `dang_ban`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['tieu_de'],
                $data['gia'],
                $data['mo_ta'] ?? null,
                $data['anh_bia'] ?? null,
                !empty($data['so_trang']) ? (int)$data['so_trang'] : null,
                !empty($data['nam_xuat_ban']) ? (int)$data['nam_xuat_ban'] : null,
                (int)($data['so_luong_ton'] ?? 0),
                !empty($data['the_loai_id']) ? (int)$data['the_loai_id'] : null,
                !empty($data['nxb_id']) ? (int)$data['nxb_id'] : null,
                isset($data['dang_ban']) ? (int)$data['dang_ban'] : 1
            ]);
            $bookId = (int)$this->db->lastInsertId();

            // Lưu liên kết tác giả
            if (!empty($authorIds)) {
                $authorStmt = $this->db->prepare("INSERT INTO `sach_tac_gia` (`sach_id`, `tac_gia_id`) VALUES (?, ?)");
                foreach ($authorIds as $authorId) {
                    if ((int)$authorId > 0) {
                        $authorStmt->execute([$bookId, (int)$authorId]);
                    }
                }
            }

            // Nếu có số lượng tồn khởi tạo, tự động tạo phiếu nhập kho đầu kỳ
            $initialStock = (int)($data['so_luong_ton'] ?? 0);
            if ($initialStock > 0 && !empty($data['user_id'])) {
                $slipStmt = $this->db->prepare(
                    "INSERT INTO `phieu_kho` (`sach_id`, `nguoi_dung_id`, `loai`, `so_luong`, `ghi_chu`, `ngay_tao`) 
                     VALUES (?, ?, 'nhap', ?, ?, NOW())"
                );
                $slipStmt->execute([
                    $bookId,
                    (int)$data['user_id'],
                    $initialStock,
                    'Khởi tạo tồn kho ban đầu khi tạo tựa sách mới'
                ]);
            }

            $this->commit();
            return $bookId;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Cập nhật thông tin sách và đồng bộ tác giả
     */
    public function updateBook(int $id, array $data, array $authorIds = []): bool {
        try {
            $this->beginTransaction();

            $fields = [
                'tieu_de = ?',
                'gia = ?',
                'mo_ta = ?',
                'so_trang = ?',
                'nam_xuat_ban = ?',
                'the_loai_id = ?',
                'nxb_id = ?',
                'dang_ban = ?'
            ];
            $params = [
                $data['tieu_de'],
                $data['gia'],
                $data['mo_ta'] ?? null,
                !empty($data['so_trang']) ? (int)$data['so_trang'] : null,
                !empty($data['nam_xuat_ban']) ? (int)$data['nam_xuat_ban'] : null,
                !empty($data['the_loai_id']) ? (int)$data['the_loai_id'] : null,
                !empty($data['nxb_id']) ? (int)$data['nxb_id'] : null,
                isset($data['dang_ban']) ? (int)$data['dang_ban'] : 1
            ];

            if (isset($data['anh_bia']) && $data['anh_bia'] !== '') {
                $fields[] = 'anh_bia = ?';
                $params[] = $data['anh_bia'];
            }

            $params[] = $id;
            $sql = "UPDATE `sach` SET " . implode(', ', $fields) . " WHERE `id` = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // Cập nhật tác giả nếu danh sách được truyền
            if (!empty($authorIds)) {
                $this->db->prepare("DELETE FROM `sach_tac_gia` WHERE `sach_id` = ?")->execute([$id]);
                $authorStmt = $this->db->prepare("INSERT INTO `sach_tac_gia` (`sach_id`, `tac_gia_id`) VALUES (?, ?)");
                foreach ($authorIds as $authorId) {
                    if ((int)$authorId > 0) {
                        $authorStmt->execute([$id, (int)$authorId]);
                    }
                }
            }

            $this->commit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Bật / Tắt trạng thái kinh doanh của sách
     */
    public function toggleStatus(int $id): bool {
        $sql = "UPDATE `sach` SET `dang_ban` = 1 - `dang_ban` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Xóa sách an toàn (nếu đã có đơn hàng thì chuyển sang ngừng bán)
     */
    public function deleteBook(int $id): array {
        // Kiểm tra xem sách đã từng có trong đơn hàng chưa
        $checkOrderSql = "SELECT COUNT(*) FROM `chi_tiet_don_hang` WHERE `sach_id` = ?";
        $orderCount = (int)($this->fetchOne($checkOrderSql, [$id])['COUNT(*)'] ?? 0);

        if ($orderCount > 0) {
            // Đã có đơn hàng => Chuyển sang ngừng bán để bảo toàn lịch sử dữ liệu
            $this->execute("UPDATE `sach` SET `dang_ban` = 0 WHERE `id` = ?", [$id]);
            return [
                'action'  => 'soft_delete',
                'message' => "Tựa sách này đã có trong {$orderCount} đơn hàng, hệ thống đã tự động chuyển sang trạng thái 'Ngừng bán' để bảo toàn dữ liệu lịch sử."
            ];
        }

        try {
            $this->beginTransaction();
            $this->execute("DELETE FROM `phieu_kho` WHERE `sach_id` = ?", [$id]);
            $this->execute("DELETE FROM `sach_tac_gia` WHERE `sach_id` = ?", [$id]);
            $this->execute("DELETE FROM `danh_gia` WHERE `sach_id` = ?", [$id]);
            $this->execute("DELETE FROM `chi_tiet_gio_hang` WHERE `sach_id` = ?", [$id]);
            $this->execute("DELETE FROM `sach` WHERE `id` = ?", [$id]);
            $this->commit();

            return [
                'action'  => 'deleted',
                'message' => 'Đã xóa hoàn toàn tựa sách khỏi hệ thống CSDL thành công.'
            ];
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->rollBack();
            }
            throw $e;
        }
    }
}
