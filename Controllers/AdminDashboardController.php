<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;

class AdminDashboardController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $db = \Core\Database::getInstance()->getConnection();

        // Lấy thống kê cơ bản từ CSDL
        $totalUsers = (int)$db->query("SELECT COUNT(*) FROM `nguoi_dung`")->fetchColumn();
        $totalBooks = (int)$db->query("SELECT COUNT(*) FROM `sach`")->fetchColumn();
        $totalOrders = (int)$db->query("SELECT COUNT(*) FROM `don_hang`")->fetchColumn();
        $totalStock = (int)$db->query("SELECT SUM(so_luong_ton) FROM `sach`")->fetchColumn();

        $this->view('admin.dashboard', [
            'title'       => 'Tổng Quan Quản Trị - ' . APP_NAME,
            'totalUsers'  => $totalUsers,
            'totalBooks'  => $totalBooks,
            'totalOrders' => $totalOrders,
            'totalStock'  => $totalStock
        ], 'admin');
    }
}
