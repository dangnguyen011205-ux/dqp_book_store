<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\InventorySlip;
use Models\Book;

class AdminInventoryController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Mặc định chuyển hướng sang tab Phiếu kho trong trang quản trị
     */
    public function index(): void {
        redirect('admin/sach?tab=slips');
    }

    /**
     * Xử lý tạo phiếu kho mới (Nhập, Xuất, Điều chỉnh)
     */
    public function createSlip(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/sach?tab=slips');
        }

        $bookId = (int)($_POST['sach_id'] ?? 0);
        $slipType = trim($_POST['slip_type'] ?? 'nhap');
        $quantity = (int)($_POST['quantity'] ?? 0);
        $note = trim($_POST['ghi_chu'] ?? '');
        $adjustDirection = trim($_POST['adjust_direction'] ?? 'giam');

        if ($bookId <= 0) {
            set_flash('error', 'Vui lòng chọn tựa sách áp dụng phiếu kho.');
            redirect('admin/sach?tab=slips');
        }

        if ($quantity <= 0) {
            set_flash('error', 'Số lượng luân chuyển kho phải lớn hơn 0.');
            redirect('admin/sach?tab=slips');
        }

        $currentUser = Auth::user();
        $userId = $currentUser ? (int)$currentUser['id'] : 1;

        $slipModel = new InventorySlip();
        $result = $slipModel->createSlip(
            $bookId,
            $userId,
            $slipType,
            $quantity,
            $note,
            $adjustDirection
        );

        if ($result['success']) {
            set_flash('success', $result['message']);
        } else {
            set_flash('error', $result['message']);
        }

        redirect('admin/sach?tab=slips');
    }
}
