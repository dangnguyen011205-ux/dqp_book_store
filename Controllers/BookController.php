<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Book;
use Models\Category;
use Models\Author;
use Models\Publisher;
use Models\Review;

class BookController extends Controller {
    protected Book $bookModel;
    protected Category $categoryModel;
    protected Author $authorModel;
    protected Publisher $publisherModel;
    protected Review $reviewModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
        $this->authorModel = new Author();
        $this->publisherModel = new Publisher();
        $this->reviewModel = new Review();
    }

    /**
     * Danh mục sách, Tìm kiếm & Bộ lọc đa tiêu chí
     */
    public function index(): void {
        $filters = [
            'q'            => trim($_GET['q'] ?? ''),
            'the_loai'     => $_GET['the_loai'] ?? null,
            'tac_gia'      => $_GET['tac_gia'] ?? null,
            'nxb'          => $_GET['nxb'] ?? null,
            'gia_min'      => isset($_GET['gia_min']) && $_GET['gia_min'] !== '' ? (float)$_GET['gia_min'] : null,
            'gia_max'      => isset($_GET['gia_max']) && $_GET['gia_max'] !== '' ? (float)$_GET['gia_max'] : null,
            'so_sao'       => isset($_GET['so_sao']) && $_GET['so_sao'] !== '' ? (float)$_GET['so_sao'] : null,
            'con_hang'     => !empty($_GET['con_hang']) ? 1 : null,
            'sort'         => $_GET['sort'] ?? 'moi_nhat'
        ];

        // Xử lý các friendly route đặc biệt:
        $currentUrl = trim($_GET['url'] ?? '', '/');
        if ($currentUrl === 'sach-moi-ve') {
            $filters['sort'] = 'moi_nhat';
        } elseif ($currentUrl === 'sach-ban-chay') {
            $filters['sort'] = 'ban_chay';
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;

        $results = $this->bookModel->filterBooks($filters, $page, $perPage);
        $categories = $this->categoryModel->getAll();
        $authors = $this->authorModel->getAll();
        $publishers = $this->publisherModel->getAll();

        $this->view('client.catalog', [
            'title'       => 'Tìm Kiếm & Khám Phá Sách | ' . APP_NAME,
            'books'       => $results['data'],
            'total'       => $results['total'],
            'currentPage' => $results['page'],
            'perPage'     => $results['perPage'],
            'totalPages'  => $results['totalPages'],
            'filters'     => $filters,
            'categories'  => $categories,
            'authors'     => $authors,
            'publishers'  => $publishers
        ]);
    }

    /**
     * Chi tiết sách & Đánh giá
     */
    public function detail(string $id): void {
        $bookId = (int)$id;
        $book = $this->bookModel->getDetail($bookId);

        if (!$book) {
            http_response_code(404);
            $this->view('client.404', [
                'message' => 'Tác phẩm bạn đang tìm kiếm không tồn tại hoặc đã ngừng phát hành.'
            ], null);
            return;
        }

        $relatedBooks = $this->bookModel->getRelatedBooks($bookId, $book['the_loai_id'], 5);
        $reviews = $this->reviewModel->getByBookId($bookId, 'hien_thi');
        $ratingSummary = $this->reviewModel->getRatingSummary($bookId);

        // Kiểm tra quyền đánh giá của user
        $canReview = false;
        $hasReviewed = false;
        $reviewBlockReason = '';

        if (Auth::check()) {
            $userId = Auth::id();
            $hasReviewed = $this->reviewModel->hasUserReviewed($userId, $bookId);

            if ($hasReviewed) {
                $reviewBlockReason = 'Bạn đã gửi đánh giá cho tác phẩm này.';
            } else {
                $hasPurchased = $this->reviewModel->hasUserPurchased($userId, $bookId);
                if ($hasPurchased) {
                    $canReview = true;
                } else {
                    $reviewBlockReason = 'Bạn chỉ có thể gửi nhận xét sau khi đã mua sách thành công tại DQP Book Store.';
                }
            }
        } else {
            $reviewBlockReason = 'Vui lòng đăng nhập tài khoản đã mua sách để gửi nhận xét.';
        }

        $this->view('client.detail', [
            'title'             => htmlspecialchars($book['tieu_de']) . ' | ' . APP_NAME,
            'book'              => $book,
            'relatedBooks'      => $relatedBooks,
            'reviews'           => $reviews,
            'ratingSummary'     => $ratingSummary,
            'canReview'         => $canReview,
            'hasReviewed'       => $hasReviewed,
            'reviewBlockReason' => $reviewBlockReason
        ]);
    }

    /**
     * Xử lý gửi đánh giá của độc giả
     */
    public function addReview(): void {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = Auth::id();
            $bookId = (int)($_POST['sach_id'] ?? 0);
            $soSao = (int)($_POST['so_sao'] ?? 5);
            $noiDung = trim($_POST['noi_dung'] ?? '');

            if ($bookId <= 0 || $soSao < 1 || $soSao > 5 || empty($noiDung)) {
                flash('error', 'Vui lòng chọn số sao đánh giá (1-5 sao) và nhập nội dung nhận xét.');
                redirect('chi-tiet/' . $bookId . '#reviews-section');
            }

            // Kiểm tra đã đánh giá chưa
            if ($this->reviewModel->hasUserReviewed($userId, $bookId)) {
                flash('error', 'Mỗi độc giả chỉ được gửi 1 lần đánh giá cho mỗi tác phẩm.');
                redirect('chi-tiet/' . $bookId . '#reviews-section');
            }

            // Kiểm tra đã mua hàng chưa
            if (!$this->reviewModel->hasUserPurchased($userId, $bookId)) {
                flash('error', 'Bạn chỉ có thể gửi nhận xét sau khi đã mua cuốn sách này thành công.');
                redirect('chi-tiet/' . $bookId . '#reviews-section');
            }

            // Tạo đánh giá (chờ duyệt)
            $this->reviewModel->create([
                'nguoi_dung_id' => $userId,
                'sach_id'       => $bookId,
                'so_sao'        => $soSao,
                'noi_dung'      => $noiDung,
                'trang_thai'    => 'cho_duyet'
            ]);

            flash('success', 'Cảm ơn bạn đã gửi cảm nhận! Nhận xét của bạn sẽ được hiển thị sau khi ban quản trị phê duyệt.');
            redirect('chi-tiet/' . $bookId . '#reviews-section');
        }

        redirect('trang-chu');
    }
}
