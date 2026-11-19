<?php
namespace Models;

use Core\Model;

class FlashSale extends Model
{
    private string $joinSach = "
        SELECT fs.*, 
               s.tieu_de, s.gia, s.anh_bia, s.so_luong_ton, s.luot_ban
        FROM flash_sale fs
        JOIN sach s ON s.id = fs.sach_id
    ";

    /**
     * Lấy tất cả flash sale (có JOIN sach)
     */
    public function getAll(int $limit = 50): array
    {
        return $this->fetchAll(
            $this->joinSach . " ORDER BY fs.ngay_tao DESC LIMIT :lim",
            [':lim' => $limit]
        );
    }

    /**
     * Flash sale đang diễn ra: bat_dau <= NOW() AND ket_thuc > NOW()
     */
    public function getActive(int $limit = 8): array
    {
        return $this->fetchAll(
            $this->joinSach . " WHERE fs.bat_dau <= NOW() AND fs.ket_thuc > NOW()
              ORDER BY fs.ket_thuc ASC LIMIT :lim",
            [':lim' => $limit]
        );
    }

    /**
     * Flash sale sắp diễn ra: bat_dau > NOW()
     */
    public function getUpcoming(int $limit = 8): array
    {
        return $this->fetchAll(
            $this->joinSach . " WHERE fs.bat_dau > NOW() AND fs.ket_thuc > NOW()
              ORDER BY fs.bat_dau ASC LIMIT :lim",
            [':lim' => $limit]
        );
    }

    /**
     * Flash sale đã kết thúc: ket_thuc <= NOW()
     */
    public function getExpired(int $limit = 20): array
    {
        return $this->fetchAll(
            $this->joinSach . " WHERE fs.ket_thuc <= NOW()
              ORDER BY fs.ket_thuc DESC LIMIT :lim",
            [':lim' => $limit]
        );
    }

    /**
     * Lấy 1 flash sale theo id
     */
    public function getById(int $id): ?array
    {
        return $this->fetchOne(
            $this->joinSach . " WHERE fs.id = :id",
            [':id' => $id]
        );
    }

    /**
     * Tạo flash sale mới. Trả về id vừa insert.
     */
    public function create(array $data): int
    {
        $this->execute(
            "INSERT INTO flash_sale (sach_id, gia_sale, bat_dau, ket_thuc, created_by)
             VALUES (:sach_id, :gia_sale, :bat_dau, :ket_thuc, :created_by)",
            [
                ':sach_id'    => $data['sach_id'],
                ':gia_sale'   => $data['gia_sale'],
                ':bat_dau'    => $data['bat_dau'],
                ':ket_thuc'   => $data['ket_thuc'],
                ':created_by' => $data['created_by'] ?? null,
            ]
        );
        return $this->lastInsertId();
    }

    /**
     * Cập nhật flash sale
     */
    public function update(int $id, array $data): bool
    {
        return $this->execute(
            "UPDATE flash_sale
             SET sach_id = :sach_id, gia_sale = :gia_sale,
                 bat_dau = :bat_dau, ket_thuc = :ket_thuc
             WHERE id = :id",
            [
                ':sach_id'  => $data['sach_id'],
                ':gia_sale' => $data['gia_sale'],
                ':bat_dau'  => $data['bat_dau'],
                ':ket_thuc' => $data['ket_thuc'],
                ':id'       => $id,
            ]
        );
    }

    /**
     * Xóa flash sale
     */
    public function delete(int $id): bool
    {
        return $this->execute("DELETE FROM flash_sale WHERE id = :id", [':id' => $id]);
    }

    /**
     * Lấy flash sale theo sach_id
     */
    public function getByBookId(int $bookId): array
    {
        return $this->fetchAll(
            $this->joinSach . " WHERE fs.sach_id = :bid ORDER BY fs.bat_dau DESC",
            [':bid' => $bookId]
        );
    }

    /**
     * Đếm nhanh theo trạng thái
     */
    public function countByStatus(): array
    {
        $row = $this->fetchOne(
            "SELECT
               SUM(bat_dau <= NOW() AND ket_thuc > NOW()) AS active,
               SUM(bat_dau > NOW() AND ket_thuc > NOW())  AS upcoming,
               SUM(ket_thuc <= NOW())                     AS expired
             FROM flash_sale"
        );
        return $row ?? ['active' => 0, 'upcoming' => 0, 'expired' => 0];
    }
}
