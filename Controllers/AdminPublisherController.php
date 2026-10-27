<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Publisher;

class AdminPublisherController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Danh sách nhà xuất bản
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');

        $publisherModel = new Publisher();
        $publishers = $publisherModel->getAll($search);

        // Thống kê nhanh
        $totalPublishers = count($publishers);
        $totalBooks = array_sum(array_column($publishers, 'tong_sach'));
        
        $topPublisher = null;
        if (!empty($publishers)) {
            $topPublisher = $publishers[0]; // Đã sắp xếp theo tong_sach DESC
        }

        $this->view('admin/publishers/index', [
            'publishers'      => $publishers,
            'totalPublishers' => $totalPublishers,
            'totalBooks'      => $totalBooks,
            'topPublisher'    => $topPublisher,
            'search'          => $search
        ], 'admin');
    }

    /**
     * Lấy thông tin chi tiết nhà xuất bản (JSON phục vụ modal sửa)
     */
    public function detail(int $id): void {
        $publisherModel = new Publisher();
        $pub = $publisherModel->findById($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$pub) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy nhà xuất bản.']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $pub]);
        exit;
    }

    /**
     * Thêm mới nhà xuất bản
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/nha-xuat-ban');
        }

        $ten = trim($_POST['ten'] ?? '');
        $diaChi = trim($_POST['dia_chi'] ?? '');
        $lienHe = trim($_POST['lien_he'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Vui lòng nhập tên nhà xuất bản.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/nha-xuat-ban');
        }

        $publisherModel = new Publisher();
        $id = $publisherModel->create(['ten' => $ten, 'dia_chi' => $diaChi, 'lien_he' => $lienHe]);

        $msg = "Đã thêm nhà xuất bản '{$ten}' thành công!";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg, 'id' => $id]);
            exit;
        }

        set_flash('success', $msg);
        redirect('admin/nha-xuat-ban');
    }

    /**
     * Sửa nhà xuất bản
     */
    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/nha-xuat-ban');
        }

        $ten = trim($_POST['ten'] ?? '');
        $diaChi = trim($_POST['dia_chi'] ?? '');
        $lienHe = trim($_POST['lien_he'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Tên nhà xuất bản không được để trống.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/nha-xuat-ban');
        }

        $publisherModel = new Publisher();
        $ok = $publisherModel->update($id, ['ten' => $ten, 'dia_chi' => $diaChi, 'lien_he' => $lienHe]);

        $msg = $ok ? "Đã cập nhật nhà xuất bản '{$ten}' thành công!" : "Lỗi khi cập nhật nhà xuất bản.";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => $ok, 'message' => $msg]);
            exit;
        }

        if ($ok) {
            set_flash('success', $msg);
        } else {
            set_flash('error', $msg);
        }
        redirect('admin/nha-xuat-ban');
    }

    /**
     * Xóa nhà xuất bản (An toàn có ràng buộc)
     */
    public function delete(int $id): void {
        $publisherModel = new Publisher();
        $result = $publisherModel->deleteSafe($id);

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            exit;
        }

        if ($result['success']) {
            set_flash('success', $result['message']);
        } else {
            set_flash('error', $result['message']);
        }
        redirect('admin/nha-xuat-ban');
    }
}
