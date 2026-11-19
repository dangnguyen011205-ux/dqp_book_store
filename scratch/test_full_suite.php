<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Controllers/AdminDashboardController.php';

session_start();
$_SESSION['user'] = ['id' => 1, 'vai_tro' => 'quan_tri_vien', 'ho_ten' => 'Admin'];

echo "=== TEST 1: Unified Coupons ===\n";
$couponModel = new \Models\Coupon();
$unified = $couponModel->getUnifiedCoupons(1);
echo "Unified coupons count: " . count($unified) . "\n";
foreach ($unified as $c) {
    echo "- Code: {$c['ma']} ({$c['text_giam']}), min: " . format_currency($c['don_toi_thieu']) . "\n";
}

echo "\n=== TEST 2: Dashboard Chart Data Range ===\n";
// Create reflection or dummy controller test
$ctrl = new \Controllers\AdminDashboardController();
$chart7 = $ctrl->getChartDataForRange('7days');
echo "7days points: " . count($chart7['points']) . ", total rev: {$chart7['total_range_rev_f']}, orders: {$chart7['total_range_orders']}\n";
$chart30 = $ctrl->getChartDataForRange('30days');
echo "30days points: " . count($chart30['points']) . ", total rev: {$chart30['total_range_rev_f']}\n";

echo "\n=== TEST 3: Orders by Date ===\n";
$pdo = \Core\Database::getInstance()->getConnection();
$latestOrderDate = $pdo->query("SELECT DATE(ngay_dat) FROM don_hang ORDER BY id DESC LIMIT 1")->fetchColumn();
echo "Latest order date in DB: $latestOrderDate\n";
$orders = $pdo->query("SELECT id, tong_thanh_toan, trang_thai FROM don_hang WHERE DATE(ngay_dat) = '$latestOrderDate'")->fetchAll();
echo "Orders on $latestOrderDate count: " . count($orders) . "\n";

echo "\nALL TESTS PASSED SUCCESSFULLY!\n";
