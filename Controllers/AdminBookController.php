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
                'tieu_de'        => $title,
                'gia'            => $price,
                'mo_ta'          => $description,
                'anh_bia'        => $coverName,
                'so_trang'       => $pages,
                'nam_xuat_ban'   => $year,
                'so_luong_ton'   => $initialStock,
                'the_loai_id'    => $categoryId,
                'nxb_id'         => $publisherId,
                'dang_ban'       => 1,
                'user_id'        => $userId,
                'is_flash_sale'  => !empty($_POST['is_flash_sale']) ? 1 : 0,
                'gia_flash_sale' => !empty($_POST['gia_flash_sale']) ? (float)$_POST['gia_flash_sale'] : null,
                'flash_sale_end' => !empty($_POST['flash_sale_end']) ? $_POST['flash_sale_end'] : null
            ], $authorIds);

            // Tự động đồng bộ Flash Sale vào bảng flash_sale nếu kích hoạt
            if (!empty($_POST['is_flash_sale']) && !empty($_POST['gia_flash_sale'])) {
                $this->syncFlashSaleTable($bookId, 1, (float)$_POST['gia_flash_sale'], $_POST['flash_sale_start'] ?? null, $_POST['flash_sale_end'] ?? null);
            }

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
            'tieu_de'        => $title,
            'gia'            => $price,
            'mo_ta'          => $description,
            'so_trang'       => $pages,
            'nam_xuat_ban'   => $year,
            'the_loai_id'    => $categoryId,
            'nxb_id'         => $publisherId,
            'dang_ban'       => $isSelling,
            'is_flash_sale'  => !empty($_POST['is_flash_sale']) ? 1 : 0,
            'gia_flash_sale' => (!empty($_POST['gia_flash_sale']) && (float)$_POST['gia_flash_sale'] > 0) ? (float)$_POST['gia_flash_sale'] : null,
            'flash_sale_end' => !empty($_POST['flash_sale_end']) ? $_POST['flash_sale_end'] : null
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

            // Đồng bộ Flash Sale sang bảng flash_sale
            $isFs = !empty($_POST['is_flash_sale']) ? 1 : 0;
            $fsPrice = (!empty($_POST['gia_flash_sale']) && (float)$_POST['gia_flash_sale'] > 0) ? (float)$_POST['gia_flash_sale'] : null;
            $fsEnd = !empty($_POST['flash_sale_end']) ? $_POST['flash_sale_end'] : null;
            $this->syncFlashSaleTable($id, $isFs, $fsPrice, null, $fsEnd);

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
        $book = $bookModel->getDetail($id, false);
        if ($book) {
            $bookModel->toggleStatus($id);
            $newStatus = $book['dang_ban'] ? 'Ngừng bán' : 'Đang bán';
            set_flash('success', "Đã chuyển sách '{$book['tieu_de']}' sang trạng thái: {$newStatus}.");
        } else {
            set_flash('error', 'Không tìm thấy sách chỉ định.');
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (!empty($referer) && strpos($referer, 'admin/sach') !== false) {
            header('Location: ' . $referer);
            exit;
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

    /**
     * Cài đặt Flash Sale cho tựa sách (Hỗ trợ form và AJAX)
     */
    public function saveFlashSale(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/sach');
        }

        $bookModel = new Book();
        $isFlashSale = !empty($_POST['is_flash_sale']) ? 1 : 0;
        $flashPrice = !empty($_POST['gia_flash_sale']) ? (float)$_POST['gia_flash_sale'] : null;
        $startTime = !empty($_POST['flash_sale_start']) ? trim($_POST['flash_sale_start']) : null;
        $endTime = !empty($_POST['flash_sale_end']) ? trim($_POST['flash_sale_end']) : null;

        $res = $bookModel->updateFlashSale($id, $isFlashSale, $flashPrice, $endTime);
        $this->syncFlashSaleTable($id, $isFlashSale, $flashPrice, $startTime, $endTime);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $res,
                'message' => $res ? 'Cập nhật Flash Sale thành công và đã hiển thị tại Mục Flash Sale!' : 'Lỗi cập nhật Flash Sale.'
            ]);
            exit;
        }

        if ($res) {
            set_flash('success', 'Cài đặt Flash Sale cho tựa sách thành công và đã đồng bộ vào Mục Flash Sale!');
        } else {
            set_flash('error', 'Không thể cập nhật Flash Sale.');
        }

        redirect('admin/sach');
    }

    /**
     * Đồng bộ cài đặt Flash Sale từ Quản lý sách sang bảng flash_sale
     */
    private function syncFlashSaleTable(int $bookId, int $isFlashSale, ?float $flashPrice, ?string $startTime = null, ?string $endTime = null): void {
        $db = \Core\Database::getInstance()->getConnection();
        
        if ($isFlashSale && $flashPrice > 0) {
            $start = !empty($startTime) ? date('Y-m-d H:i:s', strtotime($startTime)) : date('Y-m-d H:i:s');
            $end = !empty($endTime) ? date('Y-m-d H:i:s', strtotime($endTime)) : date('Y-m-d H:i:s', strtotime($start . ' +24 hours'));

            if (strtotime($end) <= strtotime($start)) {
                $end = date('Y-m-d H:i:s', strtotime($start . ' +24 hours'));
            }

            $userId = \Core\Auth::id() ?? 1;

            // Kiểm tra xem đã có flash_sale active/upcoming cho cuốn sách này chưa
            $stmt = $db->prepare("SELECT id FROM `flash_sale` WHERE `sach_id` = ? AND `ket_thuc` >= NOW() ORDER BY `id` DESC LIMIT 1");
            $stmt->execute([$bookId]);
            $existingId = $stmt->fetchColumn();

            if ($existingId) {
                $up = $db->prepare("UPDATE `flash_sale` SET `gia_sale` = ?, `bat_dau` = ?, `ket_thuc` = ? WHERE `id` = ?");
                $up->execute([$flashPrice, $start, $end, $existingId]);
            } else {
                $ins = $db->prepare("INSERT INTO `flash_sale` (`sach_id`, `gia_sale`, `bat_dau`, `ket_thuc`, `created_by`, `ngay_tao`) VALUES (?, ?, ?, ?, ?, NOW())");
                $ins->execute([$bookId, $flashPrice, $start, $end, $userId]);
            }

            // Đồng bộ sang bảng sach
            $db->prepare("UPDATE `sach` SET `is_flash_sale` = 1, `gia_flash_sale` = ?, `flash_sale_end` = ? WHERE `id` = ?")
               ->execute([$flashPrice, $end, $bookId]);
        } else {
            // Khi tắt Flash Sale cho sách: Xóa các bản ghi flash sale tương lai/hiện tại của cuốn sách này
            $del = $db->prepare("DELETE FROM `flash_sale` WHERE `sach_id` = ? AND `ket_thuc` >= NOW()");
            $del->execute([$bookId]);

            // Cập nhật lại trong bảng sach
            $db->prepare("UPDATE `sach` SET `is_flash_sale` = 0, `gia_flash_sale` = NULL, `flash_sale_end` = NULL WHERE `id` = ?")
               ->execute([$bookId]);
        }
    }
}
