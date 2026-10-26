<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Book;
use Models\Category;
use Models\Author;
use Models\Publisher;
use Models\InventorySlip;

class AdminBookController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Hiển thị danh sách sách & tồn kho (giao diện tabs 2 phân hệ)
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');
        $category = (int)($_GET['category'] ?? 0);
        $status = trim($_GET['status'] ?? 'all');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $tab = trim($_GET['tab'] ?? 'books');

        $bookModel = new Book();
        $slipModel = new InventorySlip();
        $categoryModel = new Category();
        $authorModel = new Author();
        $publisherModel = new Publisher();

        $stats = $bookModel->getAdminStats();
        $booksData = $bookModel->getAdminBooks([
            'search'      => $search,
            'category_id' => $category,
            'status'      => $status
        ], $page, 10);

        $categories = $categoryModel->getAll();
        $authors = $authorModel->getAll();
        $publishers = $publisherModel->getAll();

        $slips = $slipModel->getAll(30, 0);
        $todaySlipsCount = $slipModel->getTodayCount();

        $this->view('admin/books/index', [
            'stats'           => $stats,
            'books'           => $booksData['data'],
            'total'           => $booksData['total'],
            'page'            => $booksData['page'],
            'perPage'         => $booksData['perPage'],
            'totalPages'      => $booksData['totalPages'],
            'categories'      => $categories,
            'authors'         => $authors,
            'publishers'      => $publishers,
            'slips'           => $slips,
            'todaySlipsCount' => $todaySlipsCount,
            'search'          => $search,
            'selectedCat'     => $category,
            'selectedStatus'  => $status,
            'activeTab'       => $tab
        ], 'admin');
    }

    /**
     * Thêm sách mới vào CSDL
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/sach');
        }

        $title = trim($_POST['tieu_de'] ?? '');
        $price = (float)($_POST['gia'] ?? 0);
        $categoryId = !empty($_POST['the_loai_id']) ? (int)$_POST['the_loai_id'] : null;
        $publisherId = !empty($_POST['nxb_id']) ? (int)$_POST['nxb_id'] : null;
        $initialStock = max(0, (int)($_POST['so_luong_ton'] ?? 0));
        $pages = !empty($_POST['so_trang']) ? (int)$_POST['so_trang'] : null;
        $year = !empty($_POST['nam_xuat_ban']) ? (int)$_POST['nam_xuat_ban'] : null;
        $description = trim($_POST['mo_ta'] ?? '');
        $authorIds = isset($_POST['tac_gia_ids']) && is_array($_POST['tac_gia_ids']) ? $_POST['tac_gia_ids'] : [];

        if (empty($title)) {
            set_flash('error', 'Vui lòng nhập tiêu đề tựa sách.');
            redirect('admin/sach');
        }

        if ($price <= 0) {
            set_flash('error', 'Vui lòng nhập giá bán niêm yết hợp lệ.');
            redirect('admin/sach');
        }

        // Xử lý upload ảnh bìa
        $coverName = null;
        if (isset($_FILES['anh_bia']) && $_FILES['anh_bia']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['anh_bia'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = ROOT_PATH . '/Public/uploads/books/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $coverName = 'book_' . uniqid('', true) . '.' . $fileExt;
                if (!move_uploaded_file($file['tmp_name'], $targetDir . $coverName)) {
                    $coverName = null;
                }
            }
        }

        if (empty($coverName) && !empty($_POST['anh_bia_url'])) {
            $coverName = trim($_POST['anh_bia_url']);
        }

        try {
            $bookModel = new Book();
            $currentUser = Auth::user();
            $userId = $currentUser ? (int)$currentUser['id'] : 1;

            $bookId = $bookModel->createBook([
                'tieu_de'      => $title,
                'gia'          => $price,
                'mo_ta'        => $description,
                'anh_bia'      => $coverName,
                'so_trang'     => $pages,
                'nam_xuat_ban' => $year,
                'so_luong_ton' => $initialStock,
                'the_loai_id'  => $categoryId,
                'nxb_id'       => $publisherId,
                'dang_ban'     => 1,
                'user_id'      => $userId
            ], $authorIds);

            set_flash('success', "Thêm tựa sách '{$title}' (#BK-{$bookId}) thành công!");
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi lưu sách: ' . $e->getMessage());
        }

        redirect('admin/sach');
    }

    /**
     * Lấy thông tin sách để sửa (AJAX / JSON) hoặc cập nhật (POST)
     */
    public function edit(int $id): void {
        $bookModel = new Book();

        // Nếu là yêu cầu JSON lấy dữ liệu
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['json']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']))) {
            header('Content-Type: application/json; charset=utf-8');
            $book = $bookModel->getBookForEdit($id);
            if (!$book) {
                http_response_code(404);
                echo json_encode(['error' => 'Không tìm thấy sách']);
            } else {
                echo json_encode($book);
            }
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/sach');
        }

        $title = trim($_POST['tieu_de'] ?? '');
        $price = (float)($_POST['gia'] ?? 0);
        $categoryId = !empty($_POST['the_loai_id']) ? (int)$_POST['the_loai_id'] : null;
        $publisherId = !empty($_POST['nxb_id']) ? (int)$_POST['nxb_id'] : null;
        $pages = !empty($_POST['so_trang']) ? (int)$_POST['so_trang'] : null;
        $year = !empty($_POST['nam_xuat_ban']) ? (int)$_POST['nam_xuat_ban'] : null;
        $description = trim($_POST['mo_ta'] ?? '');
        $isSelling = isset($_POST['dang_ban']) ? (int)$_POST['dang_ban'] : 1;
        $authorIds = isset($_POST['tac_gia_ids']) && is_array($_POST['tac_gia_ids']) ? $_POST['tac_gia_ids'] : [];

        if (empty($title)) {
            set_flash('error', 'Vui lòng nhập tiêu đề tựa sách.');
            redirect('admin/sach');
        }

        if ($price <= 0) {
            set_flash('error', 'Vui lòng nhập giá bán niêm yết hợp lệ.');
            redirect('admin/sach');
        }

        $data = [
            'tieu_de'      => $title,
            'gia'          => $price,
            'mo_ta'        => $description,
            'so_trang'     => $pages,
            'nam_xuat_ban' => $year,
            'the_loai_id'  => $categoryId,
            'nxb_id'       => $publisherId,
            'dang_ban'     => $isSelling
        ];

        // Nếu có upload ảnh bìa mới
        if (isset($_FILES['anh_bia']) && $_FILES['anh_bia']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['anh_bia'];
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = ROOT_PATH . '/Public/uploads/books/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $coverName = 'book_' . uniqid('', true) . '.' . $fileExt;
                if (move_uploaded_file($file['tmp_name'], $targetDir . $coverName)) {
                    $data['anh_bia'] = $coverName;
                }
            }
        } elseif (!empty($_POST['anh_bia_url'])) {
            $data['anh_bia'] = trim($_POST['anh_bia_url']);
        }

        try {
            $bookModel->updateBook($id, $data, $authorIds);
            set_flash('success', "Cập nhật thông tin sách '{$title}' (#BK-{$id}) thành công!");
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi cập nhật sách: ' . $e->getMessage());
        }

        redirect('admin/sach');
    }

    /**
     * Bật / Tắt trạng thái kinh doanh của sách
     */
    public function toggleStatus(int $id): void {
        $bookModel = new Book();
        $book = $bookModel->getDetail($id);
        if ($book) {
            $bookModel->toggleStatus($id);
            $newStatus = $book['dang_ban'] ? 'Ngừng bán' : 'Đang bán';
            set_flash('success', "Đã chuyển sách '{$book['tieu_de']}' sang trạng thái: {$newStatus}.");
        } else {
            set_flash('error', 'Không tìm thấy sách chỉ định.');
        }

        redirect('admin/sach');
    }

    /**
     * Xóa sách / Ngừng bán nếu đã có đơn hàng
     */
    public function delete(int $id): void {
        $bookModel = new Book();
        try {
            $result = $bookModel->deleteBook($id);
            if ($result['action'] === 'deleted') {
                set_flash('success', $result['message']);
            } else {
                set_flash('error', $result['message']);
            }
        } catch (\Exception $e) {
            set_flash('error', 'Lỗi khi xóa sách: ' . $e->getMessage());
        }

        redirect('admin/sach');
    }
}
