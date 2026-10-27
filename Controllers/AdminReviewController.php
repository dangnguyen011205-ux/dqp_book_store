<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Review;

class AdminReviewController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Màn hình danh sách và duyệt đánh giá toàn sàn
     */
    public function index(): void {
        $status = trim($_GET['status'] ?? 'tat_ca');
        $star = (isset($_GET['star']) && $_GET['star'] !== '') ? (int)$_GET['star'] : null;
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');
        $sort = trim($_GET['sort'] ?? 'moi_nhat');
        $bookId = isset($_GET['sach_id']) && (int)$_GET['sach_id'] > 0 ? (int)$_GET['sach_id'] : null;
        $page = max(1, (int)($_GET['page'] ?? 1));

        $reviewModel = new Review();

        $stats = $reviewModel->getAdminStats();
        $counts = $reviewModel->getAdminCounts();

        $filters = [
            'status'  => $status,
            'star'    => $star,
            'search'  => $search,
            'sort'    => $sort,
            'book_id' => $bookId
        ];

        $reviewsData = $reviewModel->getAdminReviews($filters, $page, 10);

        $this->view('admin/reviews/index', [
            'stats'         => $stats,
            'counts'        => $counts,
            'reviews'       => $reviewsData['data'],
            'total'         => $reviewsData['total'],
            'page'          => $reviewsData['page'],
            'perPage'       => $reviewsData['perPage'],
            'totalPages'    => $reviewsData['totalPages'],
            'currentStatus' => $status,
            'currentStar'   => $star,
            'search'        => $search,
            'currentSort'   => $sort,
            'bookId'        => $bookId
        ], 'admin');
    }

    /**
     * Duyệt hiển thị đánh giá công khai
     */
    public function approve(int $id): void {
        $reviewModel = new Review();
        $ok = $reviewModel->updateStatus($id, 'hien_thi');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? "Đã duyệt hiển thị đánh giá #{$id} thành công!" : "Lỗi khi duyệt đánh giá #{$id}."
            ]);
            exit;
        }

        if ($ok) {
            set_flash('success', "Đã duyệt hiển thị đánh giá #{$id} thành công!");
        } else {
            set_flash('error', "Không thể duyệt đánh giá #{$id}.");
        }
        redirect('admin/danh-gia');
    }

    /**
     * Ẩn đánh giá vi phạm / spam
     */
    public function hide(int $id): void {
        $reviewModel = new Review();
        $ok = $reviewModel->updateStatus($id, 'an');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? "Đã ẩn đánh giá #{$id} khỏi cửa hàng!" : "Lỗi khi ẩn đánh giá #{$id}."
            ]);
            exit;
        }

        if ($ok) {
            set_flash('success', "Đã ẩn đánh giá #{$id} khỏi cửa hàng!");
        } else {
            set_flash('error', "Không thể ẩn đánh giá #{$id}.");
        }
        redirect('admin/danh-gia');
    }

    /**
     * Xóa đánh giá vĩnh viễn
     */
    public function delete(int $id): void {
        $reviewModel = new Review();
        $ok = $reviewModel->deleteReview($id);

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? "Đã xóa đánh giá #{$id} thành công!" : "Lỗi khi xóa đánh giá #{$id}."
            ]);
            exit;
        }

        if ($ok) {
            set_flash('success', "Đã xóa đánh giá #{$id} thành công!");
        } else {
            set_flash('error', "Không thể xóa đánh giá #{$id}.");
        }
        redirect('admin/danh-gia');
    }

    /**
     * Cập nhật trạng thái trực tiếp qua dropdown hoặc AJAX
     */
    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/danh-gia');
        }

        $input = $_POST;
        if (empty($input) && ($json = file_get_contents('php://input'))) {
            $input = json_decode($json, true) ?: [];
        }

        $id = (int)($input['review_id'] ?? $input['id'] ?? 0);
        $status = trim($input['trang_thai'] ?? '');

        if ($id <= 0 || !in_array($status, ['cho_duyet', 'hien_thi', 'an'], true)) {
            $msg = 'Mã đánh giá hoặc trạng thái không hợp lệ.';
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/danh-gia');
        }

        $reviewModel = new Review();
        $ok = $reviewModel->updateStatus($id, $status);

        $statusLabels = [
            'cho_duyet' => 'Chờ duyệt',
            'hien_thi'  => 'Hiển thị công khai',
            'an'        => 'Đã ẩn'
        ];
        $label = $statusLabels[$status] ?? $status;

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $ok,
                'message' => $ok ? "Đã chuyển đánh giá #{$id} sang '{$label}' thành công!" : "Lỗi khi cập nhật trạng thái."
            ]);
            exit;
        }

        if ($ok) {
            set_flash('success', "Đã chuyển đánh giá #{$id} sang '{$label}' thành công!");
        } else {
            set_flash('error', "Không thể cập nhật trạng thái đánh giá #{$id}.");
        }
        redirect('admin/danh-gia');
    }

    /**
     * Thao tác hàng loạt (Bulk actions)
     */
    public function bulk(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/danh-gia');
        }

        $input = $_POST;
        if (empty($input) && ($json = file_get_contents('php://input'))) {
            $input = json_decode($json, true) ?: [];
        }

        $ids = $input['ids'] ?? [];
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $action = trim($input['action'] ?? '');

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));

        if (empty($ids) || empty($action)) {
            $msg = 'Vui lòng chọn ít nhất một đánh giá để thực hiện.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/danh-gia');
        }

        $reviewModel = new Review();
        $affected = 0;

        if ($action === 'duyet' || $action === 'hien_thi') {
            $affected = $reviewModel->bulkUpdateStatus($ids, 'hien_thi');
            $msg = "Đã duyệt hiển thị thành công {$affected} đánh giá!";
        } elseif ($action === 'an') {
            $affected = $reviewModel->bulkUpdateStatus($ids, 'an');
            $msg = "Đã ẩn {$affected} đánh giá khỏi cửa hàng!";
        } elseif ($action === 'xoa') {
            foreach ($ids as $id) {
                if ($reviewModel->deleteReview((int)$id)) {
                    $affected++;
                }
            }
            $msg = "Đã xóa vĩnh viễn {$affected} đánh giá!";
        } else {
            $msg = 'Thao tác hàng loạt không hợp lệ.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/danh-gia');
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'message' => $msg, 'affected' => $affected]);
            exit;
        }

        set_flash('success', $msg);
        redirect('admin/danh-gia');
    }
}
