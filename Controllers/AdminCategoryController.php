<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Category;

class AdminCategoryController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Danh sách thể loại sách
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');

        $categoryModel = new Category();
        $categories = $categoryModel->getAll($search);

        // Thống kê nhanh
        $totalCategories = count($categories);
        $totalBooks = array_sum(array_column($categories, 'tong_sach'));
        
        $topCategory = null;
        if (!empty($categories)) {
            $sorted = $categories;
            usort($sorted, fn($a, $b) => $b['tong_sach'] <=> $a['tong_sach']);
            $topCategory = $sorted[0];
        }

        $this->view('admin/categories/index', [
            'categories'      => $categories,
            'totalCategories' => $totalCategories,
            'totalBooks'      => $totalBooks,
            'topCategory'     => $topCategory,
            'search'          => $search
        ], 'admin');
    }

    /**
     * Lấy thông tin chi tiết thể loại (JSON phục vụ modal sửa)
     */
    public function detail(int $id): void {
        $categoryModel = new Category();
        $cat = $categoryModel->findById($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$cat) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thể loại.']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $cat]);
        exit;
    }

    /**
     * Thêm mới thể loại
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/the-loai');
        }

        $ten = trim($_POST['ten'] ?? '');
        $moTa = trim($_POST['mo_ta'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Vui lòng nhập tên thể loại sách.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/the-loai');
        }

        $categoryModel = new Category();
        $id = $categoryModel->create(['ten' => $ten, 'mo_ta' => $moTa]);

        $msg = "Đã thêm thể loại '{$ten}' thành công!";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg, 'id' => $id]);
            exit;
        }

        set_flash('success', $msg);
        redirect('admin/the-loai');
    }

    /**
     * Sửa thể loại
     */
    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/the-loai');
        }

        $ten = trim($_POST['ten'] ?? '');
        $moTa = trim($_POST['mo_ta'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Tên thể loại không được để trống.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/the-loai');
        }

        $categoryModel = new Category();
        $ok = $categoryModel->update($id, ['ten' => $ten, 'mo_ta' => $moTa]);

        $msg = $ok ? "Đã cập nhật thể loại '{$ten}' thành công!" : "Lỗi khi cập nhật thể loại.";
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
        redirect('admin/the-loai');
    }

    /**
     * Xóa thể loại (An toàn có ràng buộc)
     */
    public function delete(int $id): void {
        $categoryModel = new Category();
        $result = $categoryModel->deleteSafe($id);

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
        redirect('admin/the-loai');
    }
}
