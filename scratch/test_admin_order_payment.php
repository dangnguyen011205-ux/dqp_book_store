<?php
/**
 * Test Suite: Lịch sử thanh toán & Đối soát giao dịch VNPAY trên Trang Admin Đơn Hàng
 */

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
@ini_set('session.save_path', __DIR__);
require_once ROOT_PATH . '/Config/config.php';
require_once ROOT_PATH . '/Core/Helper.php';
require_once ROOT_PATH . '/Core/Database.php';
require_once ROOT_PATH . '/Core/Model.php';
require_once ROOT_PATH . '/Core/Controller.php';
require_once ROOT_PATH . '/Core/Auth.php';
require_once ROOT_PATH . '/Models/Order.php';
require_once ROOT_PATH . '/Controllers/AdminOrderController.php';

use Models\Order;
use Controllers\AdminOrderController;

echo "====================================================\n";
echo " TEST SUITE: ĐỐI SOÁT & LỊCH SỬ THANH TOÁN ADMIN\n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest(string $desc, bool $condition): void {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] " . $desc . "\n";
        $passCount++;
    } else {
        echo "[FAIL] " . $desc . "\n";
        $failCount++;
    }
}

// Giả lập đăng nhập Admin
$_SESSION['user'] = [
    'id' => 1,
    'ho_ten' => 'Administrator Test',
    'email' => 'admin@bookstore.vn',
    'vai_tro' => 'quan_tri'
];

$orderModel = new Order();

// ----------------------------------------------------
// TEST 1: Lọc đơn hàng theo payment_status
// ----------------------------------------------------
echo "--- 1. Kiểm tra bộ lọc trạng thái thanh toán (payment_status) ---\n";
$paidOrders = $orderModel->getAdminOrders(['payment_status' => 'thanh_cong'], 1, 10);
assertTest("Lấy danh sách đơn hàng đã thanh toán thành công", is_array($paidOrders['data']));

$pendingOrders = $orderModel->getAdminOrders(['payment_status' => 'cho_thanh_toan'], 1, 10);
assertTest("Lấy danh sách đơn hàng chờ thanh toán thành công", is_array($pendingOrders['data']));

// ----------------------------------------------------
// TEST 2: Tìm kiếm theo mã giao dịch VNPAY
// ----------------------------------------------------
echo "\n--- 2. Kiểm tra tìm kiếm theo mã giao dịch VNPAY ---\n";
$db = Core\Database::getInstance()->getConnection();
$sample = $db->query("SELECT id, ma_giao_dich FROM thanh_toan WHERE ma_giao_dich IS NOT NULL LIMIT 1")->fetch(PDO::FETCH_ASSOC);

if ($sample && !empty($sample['ma_giao_dich'])) {
    $searchResult = $orderModel->getAdminOrders(['search' => $sample['ma_giao_dich']], 1, 10);
    assertTest("Tìm thấy đơn hàng bằng mã giao dịch VNPAY: " . $sample['ma_giao_dich'], $searchResult['total'] >= 1);
    assertTest("Mã đơn tìm thấy khớp ID đơn hàng: #BK-" . $sample['id'], (int)$searchResult['data'][0]['id'] === (int)$sample['id']);
} else {
    echo "[SKIP] Chưa có mã giao dịch trong CSDL để tìm kiếm.\n";
}

// ----------------------------------------------------
// TEST 3: Chi tiết đơn hàng trả về đầy đủ trường đối soát
// ----------------------------------------------------
echo "\n--- 3. Kiểm tra chi tiết đơn hàng trả về trường đối soát ---\n";
$firstOrder = $db->query("SELECT id FROM don_hang ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($firstOrder) {
    $testOrderId = (int)$firstOrder['id'];
    $orderDetail = $orderModel->getOrderDetail($testOrderId);
    assertTest("Chi tiết đơn hàng tồn tại", !empty($orderDetail));
    assertTest("Có trường phuong_thuc", array_key_exists('phuong_thuc', $orderDetail));
    assertTest("Có trường trang_thai_thanh_toan", array_key_exists('trang_thai_thanh_toan', $orderDetail));
    assertTest("Có trường ma_giao_dich", array_key_exists('ma_giao_dich', $orderDetail));
    assertTest("Có trường ngay_thanh_toan", array_key_exists('ngay_thanh_toan', $orderDetail));
}

// ----------------------------------------------------
// TEST 4: Action Admin cập nhật trạng thái thanh toán (updatePayment)
// ----------------------------------------------------
echo "\n--- 4. Kiểm tra cập nhật thanh toán và ghi nhật ký lịch sử ---\n";
if ($firstOrder) {
    $testTransNo = "VNPAY_ADMIN_TEST_" . time();
    $updateSuccess = $orderModel->updatePaymentStatus(
        $testOrderId,
        'thanh_cong',
        $testTransNo,
        'Admin xác nhận đối soát'
    );
    assertTest("Cập nhật updatePaymentStatus() thành công trả về true", $updateSuccess === true);

    $refetchedPayment = $orderModel->getPaymentByOrderId($testOrderId);
    assertTest("Trạng thái thanh toán cập nhật thành 'thanh_cong'", $refetchedPayment['trang_thai'] === 'thanh_cong');
    assertTest("Mã giao dịch lưu đúng (" . $testTransNo . ")", $refetchedPayment['ma_giao_dich'] === $testTransNo);

    // Kiểm tra log được ghi vào lich_su_don_hang
    $stmtHistory = $db->prepare("SELECT ghi_chu FROM lich_su_don_hang WHERE don_hang_id = ? ORDER BY id DESC LIMIT 1");
    $stmtHistory->execute([$testOrderId]);
    $historyLog = $stmtHistory->fetch(PDO::FETCH_ASSOC);
    assertTest("Ghi log đối soát vào lich_su_don_hang", !empty($historyLog) && str_contains($historyLog['ghi_chu'], $testTransNo));
}

echo "\n====================================================\n";
echo " TỔNG KẾT: {$passCount} PASSED | {$failCount} FAILED\n";
echo "====================================================\n";
exit($failCount > 0 ? 1 : 0);
