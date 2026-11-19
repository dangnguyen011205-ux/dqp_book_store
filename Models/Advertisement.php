<?php
namespace Models;

use Core\Model;

class Advertisement extends Model {
    /**
     * Lấy danh sách quảng cáo cho admin kèm thông tin mã giảm giá và số lượt đã lưu
     */
    public function getAll(): array {
        $sql = "SELECT qc.*, mg.ma as voucher_code, mg.loai as voucher_loai, mg.gia_tri as voucher_gia_tri,
                       mg.don_toi_thieu as voucher_don_toi_thieu, mg.bat_dau as voucher_bat_dau, mg.ket_thuc as voucher_ket_thuc,
                       mg.so_luot_toi_da as voucher_so_luot_toi_da, mg.da_dung as voucher_da_dung,
                       (SELECT COUNT(*) FROM `voucher_nguoi_dung` vnd WHERE vnd.ma_giam_gia_id = qc.ma_giam_gia_id) as so_luot_da_luu
                FROM `quang_cao` qc
                LEFT JOIN `ma_giam_gia` mg ON qc.ma_giam_gia_id = mg.id
                ORDER BY qc.thu_tu ASC, qc.id DESC";
        return $this->fetchAll($sql);
    }

    /**
     * Lấy danh sách quảng cáo đang hoạt động để hiển thị ở trang chủ kèm số lượt đã lưu
     */
    public function getActiveAds(): array {
        $sql = "SELECT qc.*, mg.ma as voucher_code, mg.loai as voucher_loai, mg.gia_tri as voucher_gia_tri,
                       mg.don_toi_thieu as voucher_don_toi_thieu, mg.bat_dau as voucher_bat_dau, mg.ket_thuc as voucher_ket_thuc,
                       mg.so_luot_toi_da as voucher_so_luot_toi_da, mg.da_dung as voucher_da_dung,
                       (SELECT COUNT(*) FROM `voucher_nguoi_dung` vnd WHERE vnd.ma_giam_gia_id = qc.ma_giam_gia_id) as so_luot_da_luu
                FROM `quang_cao` qc
                LEFT JOIN `ma_giam_gia` mg ON qc.ma_giam_gia_id = mg.id
                WHERE qc.trang_thai = 1 
                  AND (qc.bat_dau IS NULL OR qc.bat_dau <= NOW())
                  AND (qc.ket_thuc IS NULL OR qc.ket_thuc >= NOW())
                ORDER BY qc.thu_tu ASC, qc.id DESC";
        return $this->fetchAll($sql);
    }

    /**
     * Lấy chi tiết quảng cáo theo ID
     */
    public function findById(int $id): ?array {
        $sql = "SELECT qc.*, mg.ma as voucher_code, mg.loai as voucher_loai, mg.gia_tri as voucher_gia_tri,
                       mg.don_toi_thieu as voucher_don_toi_thieu,
                       (SELECT COUNT(*) FROM `voucher_nguoi_dung` vnd WHERE vnd.ma_giam_gia_id = qc.ma_giam_gia_id) as so_luot_da_luu
                FROM `quang_cao` qc
                LEFT JOIN `ma_giam_gia` mg ON qc.ma_giam_gia_id = mg.id
                WHERE qc.id = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Thêm mới quảng cáo kèm voucher
     */
    public function create(array $data): int {
        $sql = "INSERT INTO `quang_cao` (`tieu_de`, `phu_de`, `noi_dung`, `anh_banner`, `ma_giam_gia_id`, `so_luot_luu_toi_da`, `mau_chu_dao`, `link_dieu_huong`, `trang_thai`, `bat_dau`, `ket_thuc`, `thu_tu`, `ngay_tao`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['tieu_de'],
            $data['phu_de'] ?? null,
            $data['noi_dung'] ?? null,
            $data['anh_banner'] ?? null,
            !empty($data['ma_giam_gia_id']) ? (int)$data['ma_giam_gia_id'] : null,
            (int)($data['so_luot_luu_toi_da'] ?? 0),
            $data['mau_chu_dao'] ?? 'from-amber-600 to-rose-700',
            $data['link_dieu_huong'] ?? 'sach',
            isset($data['trang_thai']) ? (int)$data['trang_thai'] : 1,
            !empty($data['bat_dau']) ? $data['bat_dau'] : null,
            !empty($data['ket_thuc']) ? $data['ket_thuc'] : null,
            (int)($data['thu_tu'] ?? 0)
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Cập nhật quảng cáo
     */
    public function update(int $id, array $data): bool {
        $fields = [
            'tieu_de = ?',
            'phu_de = ?',
            'noi_dung = ?',
            'ma_giam_gia_id = ?',
            'so_luot_luu_toi_da = ?',
            'mau_chu_dao = ?',
            'link_dieu_huong = ?',
            'trang_thai = ?',
            'bat_dau = ?',
            'ket_thuc = ?',
            'thu_tu = ?'
        ];
        $params = [
            $data['tieu_de'],
            $data['phu_de'] ?? null,
            $data['noi_dung'] ?? null,
            !empty($data['ma_giam_gia_id']) ? (int)$data['ma_giam_gia_id'] : null,
            (int)($data['so_luot_luu_toi_da'] ?? 0),
            $data['mau_chu_dao'] ?? 'from-amber-600 to-rose-700',
            $data['link_dieu_huong'] ?? 'sach',
            isset($data['trang_thai']) ? (int)$data['trang_thai'] : 1,
            !empty($data['bat_dau']) ? $data['bat_dau'] : null,
            !empty($data['ket_thuc']) ? $data['ket_thuc'] : null,
            (int)($data['thu_tu'] ?? 0)
        ];

        if (isset($data['anh_banner']) && $data['anh_banner'] !== '') {
            $fields[] = 'anh_banner = ?';
            $params[] = $data['anh_banner'];
        }

        $params[] = $id;
        $sql = "UPDATE `quang_cao` SET " . implode(', ', $fields) . " WHERE `id` = ?";
        return $this->execute($sql, $params);
    }

    /**
     * Bật / Tắt trạng thái hiển thị
     */
    public function toggleStatus(int $id): bool {
        $sql = "UPDATE `quang_cao` SET `trang_thai` = 1 - `trang_thai` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Xóa quảng cáo
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM `quang_cao` WHERE `id` = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Khách hàng lưu voucher vào ví cá nhân
     */
    public function saveUserVoucher(int $userId, int $couponId): array {
        // Kiểm tra xem voucher có tồn tại và còn hạn không
        $coupon = $this->fetchOne("SELECT * FROM `ma_giam_gia` WHERE `id` = ?", [$couponId]);
        if (!$coupon) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }

        // Kiểm tra xem đã lưu chưa
        $existing = $this->fetchOne(
            "SELECT * FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ? AND `ma_giam_gia_id` = ?",
            [$userId, $couponId]
        );

        if ($existing) {
            return [
                'success' => true,
                'already_saved' => true,
                'code' => $coupon['ma'],
                'message' => "Bạn đã lưu voucher '{$coupon['ma']}' trước đó rồi!"
            ];
        }

        // Kiểm tra số lượng khách hàng tối đa được lưu voucher từ chiến dịch quảng cáo
        $ad = $this->fetchOne(
            "SELECT * FROM `quang_cao` WHERE `ma_giam_gia_id` = ? AND `trang_thai` = 1 ORDER BY `id` DESC LIMIT 1",
            [$couponId]
        );

        if ($ad && (int)$ad['so_luot_luu_toi_da'] > 0) {
            $cntSaved = (int)($this->fetchOne(
                "SELECT COUNT(*) AS total_saved FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` = ?",
                [$couponId]
            )['total_saved'] ?? 0);

            if ($cntSaved >= (int)$ad['so_luot_luu_toi_da']) {
                return [
                    'success' => false,
                    'is_full' => true,
                    'code'    => $coupon['ma'],
                    'message' => "Rất tiếc! Số lượng voucher ưu đãi của chương trình này đã được lưu hết ({$cntSaved}/{$ad['so_luot_luu_toi_da']} lượt)."
                ];
            }
        }

        $sql = "INSERT INTO `voucher_nguoi_dung` (`nguoi_dung_id`, `ma_giam_gia_id`, `ngay_luu`, `trang_thai`)
                VALUES (?, ?, NOW(), 'chua_dung')";
        $this->execute($sql, [$userId, $couponId]);

        return [
            'success' => true,
            'already_saved' => false,
            'code' => $coupon['ma'],
            'message' => "Đã lưu mã ưu đãi '{$coupon['ma']}' vào ví voucher của bạn thành công!"
        ];
    }

    /**
     * Lấy danh sách voucher đã lưu của một người dùng
     */
    public function getUserSavedVouchers(int $userId): array {
        $sql = "SELECT vnd.*, mg.ma, mg.loai, mg.gia_tri, mg.don_toi_thieu, mg.bat_dau, mg.ket_thuc,
                       (mg.ket_thuc >= NOW() AND (mg.so_luot_toi_da = 0 OR vnd.so_lan_da_dung < mg.so_luot_toi_da)) as is_valid
                FROM `voucher_nguoi_dung` vnd
                JOIN `ma_giam_gia` mg ON vnd.ma_giam_gia_id = mg.id
                WHERE vnd.nguoi_dung_id = ?
                ORDER BY vnd.id DESC";
        return $this->fetchAll($sql, [$userId]);
    }

    /**
     * Kiểm tra xem người dùng đã lưu voucher cụ thể nào chưa
     */
    public function hasUserSaved(int $userId, int $couponId): bool {
        $cnt = (int)$this->fetchOne(
            "SELECT COUNT(*) as c FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ? AND `ma_giam_gia_id` = ?",
            [$userId, $couponId]
        )['c'];
        return $cnt > 0;
    }
}
