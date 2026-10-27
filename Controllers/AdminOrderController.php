<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Order;

class AdminOrderController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Màn hình danh sách và lọc đơn hàng toàn hệ thống
     */
    public function index(): void {
        $search = trim($_GET['q'] ?? $_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? 'tat_ca');
        $dateFrom = trim($_GET['tu_ngay'] ?? $_GET['date_from'] ?? '');
        $dateTo = trim($_GET['den_ngay'] ?? $_GET['date_to'] ?? '');
        $paymentMethod = trim($_GET['phuong_thuc'] ?? $_GET['payment_method'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $orderModel = new Order();

        $stats = $orderModel->getAdminStats();
        $counts = $orderModel->getAdminOrderCounts();

        $filters = [
            'search'         => $search,
            'status'         => $status,
            'date_from'      => $dateFrom,
            'date_to'        => $dateTo,
            'payment_method' => $paymentMethod
        ];

        $ordersData = $orderModel->getAdminOrders($filters, $page, 10);

        $this->view('admin/orders/index', [
            'stats'         => $stats,
            'counts'        => $counts,
            'orders'        => $ordersData['data'],
            'total'         => $ordersData['total'],
            'page'          => $ordersData['page'],
            'perPage'       => $ordersData['perPage'],
            'totalPages'    => $ordersData['totalPages'],
            'search'        => $search,
            'currentStatus' => $status,
            'dateFrom'      => $dateFrom,
            'dateTo'        => $dateTo,
            'paymentMethod' => $paymentMethod
        ], 'admin');
    }

    /**
     * Lấy chi tiết đơn hàng (Trả về JSON cho slide-over drawer hoặc xem chi tiết)
     */
    public function detail(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->getOrderDetail($id);

        if (!$order) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['json']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']))) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
                exit;
            }
            set_flash('error', 'Không tìm thấy đơn hàng #BK-' . $id);
            redirect('admin/don-hang');
        }

        // Nếu là yêu cầu AJAX JSON lấy thông tin đổ vào Slide-Over Drawer
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['json']) || !empty($_SERVER['HTTP_X_REQUESTED_WITH']))) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'data'    => $order
            ]);
            exit;
        }

        // Fallback: Chuyển hướng về trang quản lý đơn với drawer mở
        redirect('admin/don-hang?open=' . $id);
    }

    /**
     * Cập nhật trạng thái đơn hàng (Quick status changer & drawer actions)
     */
    public function updateStatus(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/don-hang');
        }

        // Hỗ trợ nhận dữ liệu từ FormData hoặc raw JSON
        $input = $_POST;
        if (empty($input) && ($json = file_get_contents('php://input'))) {
            $input = json_decode($json, true) ?: [];
        }

        $orderId = (int)($input['order_id'] ?? 0);
        $newStatus = trim($input['trang_thai'] ?? '');
        $note = trim($input['ghi_chu'] ?? '');
        $paymentStatus = !empty($input['trang_thai_thanh_toan']) ? trim($input['trang_thai_thanh_toan']) : null;

        if ($orderId <= 0 || empty($newStatus)) {
            $msg = 'Mã đơn hàng hoặc trạng thái cần cập nhật không hợp lệ.';
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => $msg]);
                exit;
            }
            set_flash('error', $msg);
            redirect('admin/don-hang');
        }

        $currentUser = Auth::user();
        $adminId = $currentUser ? (int)$currentUser['id'] : 1;

        $orderModel = new Order();
        $result = $orderModel->adminUpdateStatus($orderId, $newStatus, $note, $paymentStatus, $adminId);

        // Phản hồi JSON nếu là AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            exit;
        }

        // Phản hồi Form thông thường
        if ($result['success']) {
            set_flash('success', $result['message']);
        } else {
            set_flash('error', $result['message']);
        }

        redirect('admin/don-hang');
    }
}
