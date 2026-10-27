<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Author;

class AdminAuthorController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Danh sách tác giả
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');

        $authorModel = new Author();
        $authors = $authorModel->getAll($search);

        // Thống kê nhanh
        $totalAuthors = count($authors);
        $totalBooks = array_sum(array_column($authors, 'tong_sach'));
        
        $topAuthor = null;
        if (!empty($authors)) {
            $topAuthor = $authors[0]; // Đã sắp xếp theo tong_sach DESC
        }

        $this->view('admin/authors/index', [
            'authors'      => $authors,
            'totalAuthors' => $totalAuthors,
            'totalBooks'   => $totalBooks,
            'topAuthor'    => $topAuthor,
            'search'       => $search
        ], 'admin');
    }

    /**
     * Lấy thông tin chi tiết tác giả (JSON phục vụ modal sửa)
     */
    public function detail(int $id): void {
        $authorModel = new Author();
        $author = $authorModel->findById($id);

        header('Content-Type: application/json; charset=utf-8');
        if (!$author) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy tác giả.']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $author]);
        exit;
    }

    /**
     * Thêm mới tác giả
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/tac-gia');
        }

        $ten = trim($_POST['ten'] ?? '');
        $tieuSu = trim($_POST['tieu_su'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Vui lòng nhập tên tác giả.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/tac-gia');
        }

        $authorModel = new Author();
        $id = $authorModel->create(['ten' => $ten, 'tieu_su' => $tieuSu]);

        $msg = "Đã thêm tác giả '{$ten}' thành công!";
        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg, 'id' => $id]);
            exit;
        }

        set_flash('success', $msg);
        redirect('admin/tac-gia');
    }

    /**
     * Sửa tác giả
     */
    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/tac-gia');
        }

        $ten = trim($_POST['ten'] ?? '');
        $tieuSu = trim($_POST['tieu_su'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ten)) {
            $msg = 'Tên tác giả không được để trống.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/tac-gia');
        }

        $authorModel = new Author();
        $ok = $authorModel->update($id, ['ten' => $ten, 'tieu_su' => $tieuSu]);

        $msg = $ok ? "Đã cập nhật tác giả '{$ten}' thành công!" : "Lỗi khi cập nhật tác giả.";
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
        redirect('admin/tac-gia');
    }

    /**
     * Xóa tác giả (An toàn có ràng buộc)
     */
    public function delete(int $id): void {
        $authorModel = new Author();
        $result = $authorModel->deleteSafe($id);

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
        redirect('admin/tac-gia');
    }
}
