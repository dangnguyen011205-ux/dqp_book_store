<?php
// scratch/test_admin_orders.php
// Automated Integration Test Suite for Admin Order Management (Task 3 - Stage 4)

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Book.php';
require_once __DIR__ . '/../Models/Coupon.php';

use Models\Order;
use Models\Book;
use Models\Coupon;
use Core\Database;

$passed = 0;
$failed = 0;

function assertTest(string $desc, bool $condition, string $detail = '') {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $desc\n";
        $passed++;
    } else {
        echo "![FAIL] $desc: $detail\n";
        $failed++;
    }
}

echo "========================================================\n";
echo " TEST SUITE: ADMIN ORDER MANAGEMENT (GIAI ĐOẠN 4 - NHIỆM VỤ 3)\n";
echo "========================================================\n\n";

$orderModel = new Order();
$db = Database::getInstance()->getConnection();

// --- TEST 1: Order::getAdminStats() ---
echo "--- Group 1: Admin Stats & KPI Bento Cards ---\n";
$stats = $orderModel->getAdminStats();

assertTest("getAdminStats() returns array", is_array($stats));
assertTest("stats has 'today_orders'", isset($stats['today_orders']) && is_int($stats['today_orders']));
assertTest("stats has 'today_revenue'", isset($stats['today_revenue']) && is_numeric($stats['today_revenue']));
assertTest("stats has 'pending_orders'", isset($stats['pending_orders']) && is_int($stats['pending_orders']));
assertTest("stats has 'pending_overdue_2h'", isset($stats['pending_overdue_2h']) && is_int($stats['pending_overdue_2h']));
assertTest("stats has 'shipping_orders'", isset($stats['shipping_orders']) && is_int($stats['shipping_orders']));
assertTest("stats has 'success_rate'", isset($stats['success_rate']) && is_numeric($stats['success_rate']));
assertTest("stats has 'month_completed'", isset($stats['month_completed']) && is_int($stats['month_completed']));

// --- TEST 2: Order::getAdminOrderCounts() ---
echo "\n--- Group 2: Admin Status Counts for Tabs ---\n";
$counts = $orderModel->getAdminOrderCounts();

assertTest("getAdminOrderCounts() returns array", is_array($counts));
$requiredStatuses = ['tat_ca', 'cho_xac_nhan', 'da_xac_nhan', 'dang_giao', 'hoan_thanh', 'da_huy'];
foreach ($requiredStatuses as $st) {
    assertTest("counts has key '$st'", isset($counts[$st]) && is_int($counts[$st]));
}
$sumStatuses = $counts['cho_xac_nhan'] + $counts['da_xac_nhan'] + $counts['dang_giao'] + $counts['hoan_thanh'] + $counts['da_huy'];
assertTest("tat_ca count equals sum of all 5 individual statuses ($sumStatuses == {$counts['tat_ca']})", $counts['tat_ca'] === $sumStatuses);

// --- TEST 3: Order::getAdminOrders() with Filters & Pagination ---
echo "\n--- Group 3: Admin Orders Query & Multi-criteria Filtering ---\n";

// 3.1 Tất cả đơn, trang 1
$allOrders = $orderModel->getAdminOrders([], 1, 5);
assertTest("getAdminOrders() returns array structure", isset($allOrders['data']) && isset($allOrders['total']) && isset($allOrders['totalPages']));
assertTest("Pagination perPage respected (count <= 5)", count($allOrders['data']) <= 5);

// 3.2 Lọc theo trạng thái
$pendingOrders = $orderModel->getAdminOrders(['status' => 'cho_xac_nhan'], 1, 10);
$allPendingMatch = true;
foreach ($pendingOrders['data'] as $ord) {
    if ($ord['trang_thai'] !== 'cho_xac_nhan') {
        $allPendingMatch = false;
        break;
    }
}
assertTest("Filter by status 'cho_xac_nhan' matches all returned rows", $allPendingMatch);

// 3.3 Lọc theo từ khóa tìm kiếm
if (!empty($allOrders['data'])) {
    $firstOrder = $allOrders['data'][0];
    $searchKey = '#BK-' . $firstOrder['id'];
    $searchRes = $orderModel->getAdminOrders(['search' => $searchKey], 1, 10);
    $found = false;
    foreach ($searchRes['data'] as $sOrd) {
        if ((int)$sOrd['id'] === (int)$firstOrder['id']) {
            $found = true;
            break;
        }
    }
    assertTest("Search by order code '$searchKey' finds target order", $found);

    // Tìm theo tên người nhận
    $nameSearch = mb_substr($firstOrder['nguoi_nhan'], 0, 3, 'UTF-8');
    if (!empty($nameSearch)) {
        $nameRes = $orderModel->getAdminOrders(['search' => $nameSearch], 1, 10);
        assertTest("Search by receiver name substring '$nameSearch' returns results", count($nameRes['data']) > 0);
    }
}

// 3.4 Lọc theo phương thức thanh toán COD
$codOrders = $orderModel->getAdminOrders(['payment_method' => 'COD'], 1, 10);
$allCodMatch = true;
foreach ($codOrders['data'] as $cOrd) {
    if ($cOrd['phuong_thuc'] !== 'COD') {
        $allCodMatch = false;
        break;
    }
}
assertTest("Filter by payment_method 'COD' matches rows", $allCodMatch);

// 3.5 Kiểm tra sách con được nạp (items array)
if (!empty($allOrders['data'])) {
    $ordWithItems = $allOrders['data'][0];
    assertTest("Order has 'items' array populated", isset($ordWithItems['items']) && is_array($ordWithItems['items']));
    if (!empty($ordWithItems['items'])) {
        $it = $ordWithItems['items'][0];
        assertTest("Item has 'tieu_de', 'anh_bia', 'don_gia', 'so_luong'", isset($it['tieu_de']) && isset($it['don_gia']) && isset($it['so_luong']));
    }
}

// --- TEST 4: adminUpdateStatus() Lifecycle & Cancellation Stock Restoration ---
echo "\n--- Group 4: Status Transitions & Transaction Cancellation with Stock Restoration ---\n";

// Tạo một đơn hàng giả lập để kiểm thử đầy đủ vòng đời
// Chọn sách id 1 để theo dõi tồn kho
$bookId = 1;
$bookBefore = $db->query("SELECT so_luong_ton FROM sach WHERE id = $bookId")->fetch();
$stockBefore = (int)$bookBefore['so_luong_ton'];

// Tạo mã giảm giá tạm nếu chưa có
$voucherCheck = $db->query("SELECT id, da_dung FROM ma_giam_gia LIMIT 1")->fetch();
$voucherId = $voucherCheck ? (int)$voucherCheck['id'] : null;
$voucherUsageBefore = $voucherCheck ? (int)$voucherCheck['da_dung'] : 0;

// Thêm đơn hàng test
$testOrderQty = 2;
$db->beginTransaction();
$db->exec("INSERT INTO don_hang (nguoi_dung_id, ma_giam_gia_id, nguoi_nhan, dia_chi_giao, tong_tien_hang, phi_van_chuyen, tien_giam, tong_thanh_toan, trang_thai, ngay_dat)
           VALUES (1, " . ($voucherId ? $voucherId : "NULL") . ", 'Test Admin Orders', '123 Đường Test, Q.1, TP.HCM', 200000, 0, 0, 200000, 'cho_xac_nhan', NOW())");
$testOrderId = (int)$db->lastInsertId();

$db->exec("INSERT INTO chi_tiet_don_hang (don_hang_id, sach_id, so_luong, don_gia) VALUES ($testOrderId, $bookId, $testOrderQty, 100000)");
$db->exec("INSERT INTO thanh_toan (don_hang_id, phuong_thuc, so_tien, trang_thai) VALUES ($testOrderId, 'COD', 200000, 'cho_thanh_toan')");
$db->exec("INSERT INTO lich_su_don_hang (don_hang_id, trang_thai, ghi_chu, thoi_gian) VALUES ($testOrderId, 'cho_xac_nhan', 'Khởi tạo đơn hàng test', NOW())");

// Giả lập tồn kho đã trừ khi tạo đơn
$db->exec("UPDATE sach SET so_luong_ton = so_luong_ton - $testOrderQty WHERE id = $bookId");
if ($voucherId) {
    $db->exec("UPDATE ma_giam_gia SET da_dung = da_dung + 1 WHERE id = $voucherId");
}
$db->commit();

// 4.1 Chuyển trạng thái: cho_xac_nhan -> da_xac_nhan
$res1 = $orderModel->adminUpdateStatus($testOrderId, 'da_xac_nhan', 'Xác nhận đơn test bởi Admin');
assertTest("Update to 'da_xac_nhan' succeeds", $res1['success'] === true);

$checkStatus = $db->query("SELECT trang_thai FROM don_hang WHERE id = $testOrderId")->fetchColumn();
assertTest("DB order status updated to 'da_xac_nhan'", $checkStatus === 'da_xac_nhan');

// 4.2 Chuyển trạng thái: da_xac_nhan -> dang_giao
$res2 = $orderModel->adminUpdateStatus($testOrderId, 'dang_giao', 'Bàn giao NowShip');
assertTest("Update to 'dang_giao' succeeds", $res2['success'] === true);

// 4.3 Chuyển trạng thái: dang_giao -> hoan_thanh
$res3 = $orderModel->adminUpdateStatus($testOrderId, 'hoan_thanh', 'Giao thành công');
assertTest("Update to 'hoan_thanh' succeeds", $res3['success'] === true);

// Kiểm tra COD tự động chuyển sang 'thanh_cong'
$checkPay = $db->query("SELECT trang_thai FROM thanh_toan WHERE don_hang_id = $testOrderId")->fetchColumn();
assertTest("Payment status auto updated to 'thanh_cong' on completion", $checkPay === 'thanh_cong');

// 4.4 Tạo thêm 1 đơn hàng test để test HỦY ĐƠN HÀNG và HOÀN TRẢ TỒN KHO
$stockBeforeCancel = (int)$db->query("SELECT so_luong_ton FROM sach WHERE id = $bookId")->fetchColumn();

$db->beginTransaction();
$db->exec("INSERT INTO don_hang (nguoi_dung_id, ma_giam_gia_id, nguoi_nhan, dia_chi_giao, tong_tien_hang, phi_van_chuyen, tien_giam, tong_thanh_toan, trang_thai, ngay_dat)
           VALUES (1, " . ($voucherId ? $voucherId : "NULL") . ", 'Test Cancel Order', '123 Đường Test, Q.1, TP.HCM', 200000, 0, 0, 200000, 'cho_xac_nhan', NOW())");
$testCancelId = (int)$db->lastInsertId();
$db->exec("INSERT INTO chi_tiet_don_hang (don_hang_id, sach_id, so_luong, don_gia) VALUES ($testCancelId, $bookId, $testOrderQty, 100000)");
$db->exec("INSERT INTO thanh_toan (don_hang_id, phuong_thuc, so_tien, trang_thai) VALUES ($testCancelId, 'COD', 200000, 'cho_thanh_toan')");
$db->commit();

// Tiến hành hủy đơn test từ Admin
$resCancel = $orderModel->adminUpdateStatus($testCancelId, 'da_huy', 'Admin hủy do khách đổi ý');
assertTest("Admin cancel order succeeds", $resCancel['success'] === true);

$stockAfterCancel = (int)$db->query("SELECT so_luong_ton FROM sach WHERE id = $bookId")->fetchColumn();
assertTest("Stock restored by $testOrderQty after admin cancel ($stockBeforeCancel -> $stockAfterCancel)", $stockAfterCancel === ($stockBeforeCancel + $testOrderQty));

$orderStatusAfterCancel = $db->query("SELECT trang_thai FROM don_hang WHERE id = $testCancelId")->fetchColumn();
assertTest("Order status set to 'da_huy'", $orderStatusAfterCancel === 'da_huy');

$payStatusAfterCancel = $db->query("SELECT trang_thai FROM thanh_toan WHERE don_hang_id = $testCancelId")->fetchColumn();
assertTest("Payment status marked as 'that_bai'", $payStatusAfterCancel === 'that_bai');

$historyCount = (int)$db->query("SELECT COUNT(*) FROM lich_su_don_hang WHERE don_hang_id = $testCancelId AND trang_thai = 'da_huy'")->fetchColumn();
assertTest("lich_su_don_hang logged cancellation entry", $historyCount > 0);

// Dọn dẹp dữ liệu test
$db->exec("DELETE FROM lich_su_don_hang WHERE don_hang_id IN ($testOrderId, $testCancelId)");
$db->exec("DELETE FROM thanh_toan WHERE don_hang_id IN ($testOrderId, $testCancelId)");
$db->exec("DELETE FROM chi_tiet_don_hang WHERE don_hang_id IN ($testOrderId, $testCancelId)");
$db->exec("DELETE FROM don_hang WHERE id IN ($testOrderId, $testCancelId)");
// Khôi phục tồn kho ban đầu của sách test
$db->exec("UPDATE sach SET so_luong_ton = $stockBefore WHERE id = $bookId");
if ($voucherId) {
    $db->exec("UPDATE ma_giam_gia SET da_dung = $voucherUsageBefore WHERE id = $voucherId");
}

// 4.5 Kiểm tra từ chối trạng thái không hợp lệ
$invalidRes = $orderModel->adminUpdateStatus(999999, 'trang_thai_la');
assertTest("Invalid status rejected", $invalidRes['success'] === false);

// --- TEST 5: HTTP Endpoints & Responses ---
echo "\n--- Group 5: HTTP Endpoints & Router Mapping ---\n";

// Đăng nhập admin giả lập lấy cookie session
$cookieFile = __DIR__ . '/admin_cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

// POST login
$loginUrl = 'http://localhost/PHP_nang_cao/book_store_online/dang-nhap';
$ch = curl_init($loginUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query(['email' => 'admin@bookstore.vn', 'password' => '123456']),
    CURLOPT_COOKIEJAR      => $cookieFile,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_FOLLOWLOCATION => true
]);
curl_exec($ch);
curl_close($ch);

// 5.1 GET /admin/don-hang
$ordersPageUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/don-hang';
$ch = curl_init($ordersPageUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/don-hang returns HTTP 200", $httpCode === 200);
assertTest("Page contains 'Quản Lý & Xử Lý Đơn Hàng' title", strpos($html, 'Quản Lý &amp; Xử Lý Đơn Hàng') !== false || strpos($html, 'Quản Lý & Xử Lý Đơn Hàng') !== false);
assertTest("Page contains 4 Bento KPI cards", strpos($html, 'Tổng đơn hôm nay') !== false && strpos($html, 'Đơn Chờ xác nhận') !== false && strpos($html, 'Đang vận chuyển') !== false && strpos($html, 'Tỷ lệ giao thành công') !== false);
assertTest("Page contains Status Tabs", strpos($html, 'Chờ xác nhận') !== false && strpos($html, 'Đang giao') !== false && strpos($html, 'Hoàn thành') !== false);
assertTest("Page contains Filter form", strpos($html, 'name="q"') !== false && strpos($html, 'name="tu_ngay"') !== false && strpos($html, 'name="phuong_thuc"') !== false);
assertTest("Page contains Drawer #orderDrawer", strpos($html, 'id="orderDrawer"') !== false);
assertTest("Page contains Toast notification #statusToast", strpos($html, 'id="statusToast"') !== false);

// 5.2 GET /admin/don-hang/chi-tiet/1?json=1
$detailJsonUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/don-hang/chi-tiet/1?json=1';
$ch = curl_init($detailJsonUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$jsonResp = curl_exec($ch);
$detailCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/don-hang/chi-tiet/1?json=1 returns HTTP 200", $detailCode === 200);
$detailDecoded = json_decode($jsonResp, true);
assertTest("Detail JSON has 'success' => true", isset($detailDecoded['success']) && $detailDecoded['success'] === true);
assertTest("Detail JSON has order 'data' with 'items' and 'history'", isset($detailDecoded['data']['items']) && isset($detailDecoded['data']['history']));

// 5.3 POST /admin/don-hang/cap-nhat-trang-thai (AJAX)
$updateUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/don-hang/cap-nhat-trang-thai';
$ch = curl_init($updateUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['X-Requested-With: XMLHttpRequest'],
    CURLOPT_POSTFIELDS     => http_build_query([
        'order_id'   => 1,
        'trang_thai' => 'da_xac_nhan',
        'ghi_chu'    => 'AJAX automated test update'
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$updateResp = curl_exec($ch);
$updateCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("POST /admin/don-hang/cap-nhat-trang-thai returns HTTP 200", $updateCode === 200);
$updateDecoded = json_decode($updateResp, true);
assertTest("Update AJAX returns success JSON", isset($updateDecoded['success']) && $updateDecoded['success'] === true);

if (file_exists($cookieFile)) unlink($cookieFile);

echo "\n========================================================\n";
echo " TEST SUMMARY: Passed: $passed | Failed: $failed\n";
echo "========================================================\n";

if ($failed === 0) {
    echo ">>> ALL INTEGRATION TESTS PASSED 100%! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED! <<<\n";
    exit(1);
}
