<?php
namespace Models;

use Core\Model;
use PDO;
use Exception;

class InventorySlip extends Model {
    /**
     * Lấy danh sách phiếu kho kèm thông tin tựa sách và người thực hiện
     */
    public function getAll(int $limit = 20, int $offset = 0, ?string $loai = null, ?int $sachId = null, ?string $search = null): array {
        $sql = "SELECT pk.*, 
                       s.tieu_de, s.anh_bia, s.so_luong_ton, s.gia,
                       u.ho_ten, u.email
                FROM `phieu_kho` pk
                LEFT JOIN `sach` s ON pk.sach_id = s.id
                LEFT JOIN `nguoi_dung` u ON pk.nguoi_dung_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($loai)) {
            $sql .= " AND pk.loai = ?";
            $params[] = $loai;
        }

        if (!empty($sachId) && $sachId > 0) {
            $sql .= " AND pk.sach_id = ?";
            $params[] = $sachId;
        }

        if (!empty($search)) {
            $sql .= " AND (s.tieu_de LIKE ? OR pk.ghi_chu LIKE ? OR pk.id LIKE ?)";
            $keyword = '%' . $search . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY pk.id DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        $idx = 1;
        foreach ($params as $p) {
            $stmt->bindValue($idx++, $p);
        }
        $stmt->bindValue($idx++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($idx++, $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Đếm tổng số phiếu kho thỏa mãn điều kiện
     */
    public function countAll(?string $loai = null, ?int $sachId = null, ?string $search = null): int {
        $sql = "SELECT COUNT(pk.id) AS total
                FROM `phieu_kho` pk
                LEFT JOIN `sach` s ON pk.sach_id = s.id
                WHERE 1=1";
        $params = [];

        if (!empty($loai)) {
            $sql .= " AND pk.loai = ?";
            $params[] = $loai;
        }

        if (!empty($sachId) && $sachId > 0) {
            $sql .= " AND pk.sach_id = ?";
            $params[] = $sachId;
        }

        if (!empty($search)) {
            $sql .= " AND (s.tieu_de LIKE ? OR pk.ghi_chu LIKE ? OR pk.id LIKE ?)";
            $keyword = '%' . $search . '%';
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    /**
     * Đếm số lượng phiếu phát sinh hôm nay
     */
    public function getTodayCount(): int {
        $sql = "SELECT COUNT(id) FROM `phieu_kho` WHERE DATE(ngay_tao) = CURDATE()";
        $stmt = $this->db->query($sql);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    /**
     * Lấy chi tiết một phiếu kho
     */
    public function getById(int $id): ?array {
        $sql = "SELECT pk.*, 
                       s.tieu_de, s.anh_bia, s.so_luong_ton, s.gia,
                       u.ho_ten, u.email
                FROM `phieu_kho` pk
                LEFT JOIN `sach` s ON pk.sach_id = s.id
                LEFT JOIN `nguoi_dung` u ON pk.nguoi_dung_id = u.id
                WHERE pk.id = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Tạo phiếu kho an toàn với Database Transaction và SELECT ... FOR UPDATE
     *
     * @param int $bookId ID sách
     * @param int $userId ID tài khoản nhân viên / admin thực hiện
     * @param string $type Loại phiếu ('nhap', 'xuat', 'dieu_chinh')
     * @param int $quantity Số lượng tác động (> 0)
     * @param string|null $note Ghi chú chứng từ
     * @param string $adjustDirection Hướng điều chỉnh nếu là 'dieu_chinh' ('tang' hoặc 'giam')
     * @return array Kết quả thực hiện ['success' => bool, 'message' => string, 'new_stock' => int]
     */
    public function createSlip(int $bookId, int $userId, string $type, int $quantity, ?string $note = null, string $adjustDirection = 'giam'): array {
        if ($bookId <= 0 || $quantity <= 0) {
            return [
                'success' => false,
                'message' => 'Thông tin đầu sách hoặc số lượng không hợp lệ.'
            ];
        }

        $validTypes = ['nhap', 'xuat', 'dieu_chinh'];
        if (!in_array($type, $validTypes, true)) {
            return [
                'success' => false,
                'message' => 'Loại phiếu kho không hợp lệ.'
            ];
        }

        try {
            $this->beginTransaction();

            // 1. Khóa dòng kiểm tra tồn kho hiện tại
            $stmt = $this->db->prepare("SELECT id, tieu_de, so_luong_ton FROM `sach` WHERE `id` = ? FOR UPDATE");
            $stmt->execute([$bookId]);
            $book = $stmt->fetch();

            if (!$book) {
                $this->rollBack();
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy tựa sách được chỉ định.'
                ];
            }

            $currentStock = (int)$book['so_luong_ton'];
            $newStock = $currentStock;

            // 2. Tính toán lượng tồn mới theo loại nghiệp vụ
            if ($type === 'nhap') {
                $newStock = $currentStock + $quantity;
            } elseif ($type === 'xuat') {
                if ($currentStock < $quantity) {
                    $this->rollBack();
                    return [
                        'success' => false,
                        'message' => "Số lượng tồn kho hiện tại ({$currentStock} cuốn) không đủ để xuất {$quantity} cuốn."
                    ];
                }
                $newStock = $currentStock - $quantity;
            } elseif ($type === 'dieu_chinh') {
                if ($adjustDirection === 'tang') {
                    $newStock = $currentStock + $quantity;
                } else {
                    if ($currentStock < $quantity) {
                        $this->rollBack();
                        return [
                            'success' => false,
                            'message' => "Số lượng điều chỉnh giảm ({$quantity} cuốn) vượt quá tồn kho hiện tại ({$currentStock} cuốn)."
                        ];
                    }
                    $newStock = $currentStock - $quantity;
                }
            }

            // 3. Cập nhật số lượng tồn vào bảng sach
            $updateStmt = $this->db->prepare("UPDATE `sach` SET `so_luong_ton` = ? WHERE `id` = ?");
            $updateStmt->execute([$newStock, $bookId]);

            // 4. Ghi bản ghi phiếu kho
            $insertSlipStmt = $this->db->prepare(
                "INSERT INTO `phieu_kho` (`sach_id`, `nguoi_dung_id`, `loai`, `so_luong`, `ghi_chu`, `ngay_tao`) 
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $insertSlipStmt->execute([
                $bookId,
                $userId,
                $type,
                $quantity,
                $note
            ]);
            $slipId = (int)$this->db->lastInsertId();

            $this->commit();

            return [
                'success'   => true,
                'slip_id'   => $slipId,
                'new_stock' => $newStock,
                'message'   => "Tạo phiếu kho #PK-{$slipId} thành công! Tồn kho sách '{$book['tieu_de']}' hiện tại: {$newStock} cuốn."
            ];

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->rollBack();
            }
            return [
                'success' => false,
                'message' => 'Lỗi hệ thống khi tạo phiếu kho: ' . $e->getMessage()
            ];
        }
    }
}
