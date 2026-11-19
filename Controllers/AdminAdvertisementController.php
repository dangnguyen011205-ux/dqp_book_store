<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Advertisement;
use Models\Coupon;

class AdminAdvertisementController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Danh sách quảng cáo kèm voucher
     */
    public function index(): void {
        $adModel = new Advertisement();
        $couponModel = new Coupon();

        $ads = $adModel->getAll();
        $activeCoupons = $couponModel->getActiveCoupons();

        $this->view('admin.advertisements.index', [
            'title'         => 'Quản Lý Quảng Cáo & Banner Kèm Voucher - ' . APP_NAME,
            'ads'           => $ads,
            'activeCoupons' => $activeCoupons
        ], 'admin');
    }

    /**
     * Lấy dữ liệu chi tiết quảng cáo dạng JSON phục vụ Modal sửa
     */
    public function detail(int $id): void {
        $adModel = new Advertisement();
        $ad = $adModel->findById($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$ad) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy quảng cáo.']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $ad]);
        exit;
    }

    /**
     * Thêm quảng cáo mới kèm voucher
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/quang-cao');
        }

        $title = trim($_POST['tieu_de'] ?? '');
        $subtitle = trim($_POST['phu_de'] ?? '');
        $content = trim($_POST['noi_dung'] ?? '');
        $couponId = !empty($_POST['ma_giam_gia_id']) ? (int)$_POST['ma_giam_gia_id'] : null;
        $maxSaves = max(0, (int)($_POST['so_luot_luu_toi_da'] ?? 0));
        $themeColor = trim($_POST['mau_chu_dao'] ?? 'from-amber-600 to-rose-700');
        $link = trim($_POST['link_dieu_huong'] ?? 'sach');
        $order = (int)($_POST['thu_tu'] ?? 0);
        $startDate = !empty($_POST['bat_dau']) ? $_POST['bat_dau'] : null;
        $endDate = !empty($_POST['ket_thuc']) ? $_POST['ket_thuc'] : null;

        if (empty($title)) {
            set_flash('error', 'Vui lòng nhập tiêu đề chương trình quảng cáo.');
            redirect('admin/quang-cao');
        }

        // Upload banner ảnh nếu có
        $bannerName = null;
        if (isset($_FILES['anh_banner']) && $_FILES['anh_banner']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['anh_banner'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = ROOT_PATH . '/Public/uploads/banners/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $bannerName = 'banner_' . uniqid('', true) . '.' . $fileExt;
                if (!move_uploaded_file($file['tmp_name'], $targetDir . $bannerName)) {
                    $bannerName = null;
                }
            }
        }

        if (empty($bannerName) && !empty($_POST['anh_banner_url'])) {
            $bannerName = trim($_POST['anh_banner_url']);
        }

        try {
            $adModel = new Advertisement();
            $adModel->create([
                'tieu_de'         => $title,
                'phu_de'          => $subtitle,
                'noi_dung'        => $content,
                'anh_banner'      => $bannerName,
                'ma_giam_gia_id'  => $couponId,
                'so_luot_luu_toi_da' => $maxSaves,
                'mau_chu_dao'     => $themeColor,
                'link_dieu_huong' => $link,
                'trang_thai'      => 1,
                'bat_dau'         => $startDate,
                'ket_thuc'        => $endDate,
                'thu_tu'          => $order
            ]);

            set_flash('success', "Đăng quảng cáo kèm voucher '{$title}' thành công!");
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi lưu quảng cáo: ' . $e->getMessage());
        }

        redirect('admin/quang-cao');
    }

    /**
     * Sửa quảng cáo
     */
    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/quang-cao');
        }

        $title = trim($_POST['tieu_de'] ?? '');
        $subtitle = trim($_POST['phu_de'] ?? '');
        $content = trim($_POST['noi_dung'] ?? '');
        $couponId = !empty($_POST['ma_giam_gia_id']) ? (int)$_POST['ma_giam_gia_id'] : null;
        $maxSaves = max(0, (int)($_POST['so_luot_luu_toi_da'] ?? 0));
        $themeColor = trim($_POST['mau_chu_dao'] ?? 'from-amber-600 to-rose-700');
        $link = trim($_POST['link_dieu_huong'] ?? 'sach');
        $order = (int)($_POST['thu_tu'] ?? 0);
        $status = isset($_POST['trang_thai']) ? (int)$_POST['trang_thai'] : 1;
        $startDate = !empty($_POST['bat_dau']) ? $_POST['bat_dau'] : null;
        $endDate = !empty($_POST['ket_thuc']) ? $_POST['ket_thuc'] : null;

        if (empty($title)) {
            set_flash('error', 'Vui lòng nhập tiêu đề chương trình quảng cáo.');
            redirect('admin/quang-cao');
        }

        $data = [
            'tieu_de'         => $title,
            'phu_de'          => $subtitle,
            'noi_dung'        => $content,
            'ma_giam_gia_id'  => $couponId,
            'so_luot_luu_toi_da' => $maxSaves,
            'mau_chu_dao'     => $themeColor,
            'link_dieu_huong' => $link,
            'trang_thai'      => $status,
            'bat_dau'         => $startDate,
            'ket_thuc'        => $endDate,
            'thu_tu'          => $order
        ];

        // Upload ảnh mới nếu có
        if (isset($_FILES['anh_banner']) && $_FILES['anh_banner']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['anh_banner'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = ROOT_PATH . '/Public/uploads/banners/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $bannerName = 'banner_' . uniqid('', true) . '.' . $fileExt;
                if (move_uploaded_file($file['tmp_name'], $targetDir . $bannerName)) {
                    $data['anh_banner'] = $bannerName;
                }
            }
        } elseif (!empty($_POST['anh_banner_url'])) {
            $data['anh_banner'] = trim($_POST['anh_banner_url']);
        }

        try {
            $adModel = new Advertisement();
            $adModel->update($id, $data);
            set_flash('success', "Cập nhật quảng cáo '{$title}' thành công!");
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi cập nhật quảng cáo: ' . $e->getMessage());
        }

        redirect('admin/quang-cao');
    }

    /**
     * Bật / Tắt trạng thái hiển thị
     */
    public function toggleStatus(int $id): void {
        $adModel = new Advertisement();
        $ad = $adModel->findById($id);
        if ($ad) {
            $adModel->toggleStatus($id);
            $newStatus = $ad['trang_thai'] ? 'Ẩn' : 'Hiển thị';
            set_flash('success', "Đã chuyển quảng cáo '{$ad['tieu_de']}' sang: {$newStatus}.");
        } else {
            set_flash('error', 'Không tìm thấy quảng cáo.');
        }

        redirect('admin/quang-cao');
    }

    /**
     * Xóa quảng cáo
     */
    public function delete(int $id): void {
        $adModel = new Advertisement();
        try {
            $adModel->delete($id);
            set_flash('success', 'Đã xóa chương trình quảng cáo thành công.');
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi xóa quảng cáo: ' . $e->getMessage());
        }

        redirect('admin/quang-cao');
    }
}
