<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Review.php';

use Core\Database;
use Models\Order;
use Models\Review;

$db = Database::getInstance()->getConnection();
$orderModel = new Order();
$reviewModel = new Review();

echo "--- 1. Testing Admin Order Search ---\n";
// Test search with #BK-1
$res1 = $orderModel->getAdminOrders(['search' => '#BK-1'], 1, 10);
echo "Search '#BK-1': found " . count($res1['data']) . " orders. Total: " . $res1['total'] . "\n";

// Test search with raw ID
$res2 = $orderModel->getAdminOrders(['search' => '1'], 1, 10);
echo "Search '1': found " . count($res2['data']) . " orders. Total: " . $res2['total'] . "\n";

// Test search with a book keyword
$sampleBook = $db->query("SELECT tieu_de FROM sach LIMIT 1")->fetchColumn();
echo "Sample book title: {$sampleBook}\n";
$firstWord = explode(' ', trim($sampleBook))[0] ?? 'a';
$res3 = $orderModel->getAdminOrders(['search' => $firstWord], 1, 10);
echo "Search book keyword '{$firstWord}': found " . count($res3['data']) . " orders. Total: " . $res3['total'] . "\n";

echo "\n--- 2. Testing Client Order Search ---\n";
// Find a user who has orders
$sampleUserId = $db->query("SELECT nguoi_dung_id FROM don_hang WHERE nguoi_dung_id IS NOT NULL LIMIT 1")->fetchColumn();
if ($sampleUserId) {
    $userOrdersAll = $orderModel->getUserOrders($sampleUserId, null, null);
    echo "User ID {$sampleUserId} has " . count($userOrdersAll) . " orders.\n";
    if (!empty($userOrdersAll)) {
        $firstOrderId = $userOrdersAll[0]['id'];
        $userSearchCode = $orderModel->getUserOrders($sampleUserId, null, "#BK-{$firstOrderId}");
        echo "User search '#BK-{$firstOrderId}': found " . count($userSearchCode) . " orders.\n";
    }
}

echo "\n--- 3. Testing Status Filter 'da_huy' ---\n";
$resCancel = $orderModel->getAdminOrders(['status' => 'da_huy'], 1, 10);
echo "Orders with status 'da_huy': Total = " . $resCancel['total'] . "\n";
foreach ($resCancel['data'] as $co) {
    echo " - Order #BK-{$co['id']}: status={$co['trang_thai']}\n";
}

echo "\n--- 4. Testing Review Status Flow ---\n";
// Check recent reviews
$reviews = $reviewModel->getAdminReviews(['status' => 'tat_ca'], 1, 5);
echo "Total reviews: " . $reviews['total'] . "\n";
if (!empty($reviews['data'])) {
    $firstRev = $reviews['data'][0];
    echo "First review ID: {$firstRev['id']}, status: {$firstRev['trang_thai']}\n";
}

echo "\nALL TESTS FINISHED SUCCESSFULLY!\n";
