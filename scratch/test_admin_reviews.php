<?php
// scratch/test_admin_reviews.php
// Automated Integration Test Suite for Admin Review Moderation (Task 4 - Stage 4)

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Review.php';

use Models\Review;
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
echo " TEST SUITE: ADMIN REVIEW MODERATION (GIAI ĐOẠN 4 - NHIỆM VỤ 4)\n";
echo "========================================================\n\n";

$reviewModel = new Review();
$db = Database::getInstance()->getConnection();

// --- TEST 1: Review::getAdminStats() ---
echo "--- Group 1: Admin Stats & KPI Bento Cards ---\n";
$stats = $reviewModel->getAdminStats();

assertTest("getAdminStats() returns array", is_array($stats));
assertTest("stats has 'total_reviews'", isset($stats['total_reviews']) && is_int($stats['total_reviews']));
assertTest("stats has 'pending_reviews'", isset($stats['pending_reviews']) && is_int($stats['pending_reviews']));
assertTest("stats has 'approved_reviews'", isset($stats['approved_reviews']) && is_int($stats['approved_reviews']));
assertTest("stats has 'hidden_reviews'", isset($stats['hidden_reviews']) && is_int($stats['hidden_reviews']));
assertTest("stats has 'avg_rating'", isset($stats['avg_rating']) && is_numeric($stats['avg_rating']));
assertTest("stats has 'five_star_pct'", isset($stats['five_star_pct']) && is_numeric($stats['five_star_pct']));
assertTest("stats has 'satisfaction_rate'", isset($stats['satisfaction_rate']) && is_numeric($stats['satisfaction_rate']));

$totalSum = $stats['pending_reviews'] + $stats['approved_reviews'] + $stats['hidden_reviews'];
assertTest("Sum of statuses equals total reviews ($totalSum == {$stats['total_reviews']})", $totalSum === $stats['total_reviews']);

// --- TEST 2: Review::getAdminCounts() ---
echo "\n--- Group 2: Status & Star Rating Counts ---\n";
$counts = $reviewModel->getAdminCounts();

assertTest("getAdminCounts() returns array with 'status' and 'stars'", isset($counts['status']) && isset($counts['stars']));
assertTest("status has 'tat_ca', 'cho_duyet', 'hien_thi', 'an'", isset($counts['status']['tat_ca']) && isset($counts['status']['cho_duyet']) && isset($counts['status']['hien_thi']) && isset($counts['status']['an']));
assertTest("stars has 'all', 5, 4, 3, 2, 1", isset($counts['stars']['all']) && isset($counts['stars'][5]) && isset($counts['stars'][4]) && isset($counts['stars'][3]) && isset($counts['stars'][2]) && isset($counts['stars'][1]));

// --- TEST 3: Review::getAdminReviews() with Multi-criteria Filtering ---
echo "\n--- Group 3: Admin Reviews Query & Multi-criteria Filtering ---\n";

// 3.1 Tất cả đánh giá
$allReviews = $reviewModel->getAdminReviews([], 1, 5);
assertTest("getAdminReviews() returns array structure", isset($allReviews['data']) && isset($allReviews['total']) && isset($allReviews['totalPages']));
assertTest("Pagination perPage respected (count <= 5)", count($allReviews['data']) <= 5);

// 3.2 Lọc theo trạng thái 'cho_duyet'
$pendingReviews = $reviewModel->getAdminReviews(['status' => 'cho_duyet'], 1, 10);
$allPendingMatch = true;
foreach ($pendingReviews['data'] as $rev) {
    if ($rev['trang_thai'] !== 'cho_duyet') {
        $allPendingMatch = false;
        break;
    }
}
assertTest("Filter by status 'cho_duyet' returns only pending reviews", $allPendingMatch);

// 3.3 Lọc theo số sao
$fiveStarReviews = $reviewModel->getAdminReviews(['star' => 5], 1, 10);
$allFiveStarMatch = true;
foreach ($fiveStarReviews['data'] as $rev) {
    if ((int)$rev['so_sao'] !== 5) {
        $allFiveStarMatch = false;
        break;
    }
}
assertTest("Filter by star rating '5' returns only 5-star reviews", $allFiveStarMatch);

// 3.4 Tìm kiếm theo từ khóa
if (!empty($allReviews['data'])) {
    $firstRev = $allReviews['data'][0];
    $searchKey = mb_substr($firstRev['nguoi_danh_gia_ten'], 0, 3, 'UTF-8');
    $searchRes = $reviewModel->getAdminReviews(['search' => $searchKey], 1, 10);
    assertTest("Search by reviewer name substring '$searchKey' finds results", count($searchRes['data']) > 0);
}

// 3.5 Sắp xếp
$sortedAsc = $reviewModel->getAdminReviews(['sort' => 'sao_thap'], 1, 10);
$sortedDesc = $reviewModel->getAdminReviews(['sort' => 'sao_cao'], 1, 10);
if (!empty($sortedAsc['data']) && !empty($sortedDesc['data'])) {
    assertTest("Lowest star first has so_sao <= highest star first", (int)$sortedAsc['data'][0]['so_sao'] <= (int)$sortedDesc['data'][0]['so_sao']);
}

// --- TEST 4: Moderation Actions (Update status, bulk, invalid rejection) ---
echo "\n--- Group 4: Moderation Actions & Bulk Operations ---\n";

// Tạo đánh giá test
$db->exec("INSERT INTO danh_gia (nguoi_dung_id, sach_id, so_sao, noi_dung, trang_thai, ngay_tao)
           VALUES (1, 3, 5, 'Nội dung test kiểm duyệt tự động', 'cho_duyet', NOW())");
$testRevId = (int)$db->lastInsertId();

// 4.1 Duyệt hiển thị
$okApprove = $reviewModel->updateStatus($testRevId, 'hien_thi');
assertTest("updateStatus to 'hien_thi' returns true", $okApprove);
$statusAfterApprove = $db->query("SELECT trang_thai FROM danh_gia WHERE id = $testRevId")->fetchColumn();
assertTest("DB status updated to 'hien_thi'", $statusAfterApprove === 'hien_thi');

// 4.2 Ẩn đánh giá
$okHide = $reviewModel->updateStatus($testRevId, 'an');
assertTest("updateStatus to 'an' returns true", $okHide);
$statusAfterHide = $db->query("SELECT trang_thai FROM danh_gia WHERE id = $testRevId")->fetchColumn();
assertTest("DB status updated to 'an'", $statusAfterHide === 'an');

// 4.3 Thao tác hàng loạt (Bulk moderation)
$affected = $reviewModel->bulkUpdateStatus([$testRevId], 'hien_thi');
assertTest("bulkUpdateStatus updates target rows ($affected >= 1)", $affected >= 1);

// 4.4 Xóa đánh giá test
$okDel = $reviewModel->deleteReview($testRevId);
assertTest("deleteReview returns true", $okDel);
$checkDel = $db->query("SELECT id FROM danh_gia WHERE id = $testRevId")->fetch();
assertTest("Test review successfully removed from DB", $checkDel === false);

// 4.5 Từ chối trạng thái không hợp lệ
$invalidOk = $reviewModel->updateStatus(1, 'trang_thai_la');
assertTest("Invalid status rejected", $invalidOk === false);

// --- TEST 5: HTTP Endpoints & Router Mapping ---
echo "\n--- Group 5: HTTP Endpoints & Router Mapping ---\n";

$cookieFile = __DIR__ . '/admin_cookie.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

// Login as admin
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

// 5.1 GET /admin/danh-gia
$reviewPageUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/danh-gia';
$ch = curl_init($reviewPageUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/danh-gia returns HTTP 200", $httpCode === 200);
assertTest("Page contains 'Quản Lý & Duyệt Đánh Giá Khách Hàng' title", strpos($html, 'Quản Lý &amp; Duyệt Đánh Giá Khách Hàng') !== false || strpos($html, 'Quản Lý & Duyệt Đánh Giá Khách Hàng') !== false);
assertTest("Page contains 4 Bento KPI cards", strpos($html, 'Đánh giá Chờ Duyệt') !== false && strpos($html, 'Đang Hiển Thị') !== false && strpos($html, 'Điểm Đánh Giá Toàn Sàn') !== false && strpos($html, 'Đã Ẩn / Vi Phạm') !== false);
assertTest("Page contains Status Tabs", strpos($html, 'Chờ duyệt') !== false && strpos($html, 'Đang hiển thị') !== false && strpos($html, 'Đã ẩn / Báo cáo') !== false);
assertTest("Page contains Star Filter", strpos($html, 'Xếp hạng sao') !== false && strpos($html, 'Tất cả sao') !== false);
assertTest("Page contains Bulk Toolbar #bulkToolbar", strpos($html, 'id="bulkToolbar"') !== false);
assertTest("Page contains Toast notification #statusToast", strpos($html, 'id="statusToast"') !== false);

// 5.2 POST /admin/danh-gia/cap-nhat-trang-thai (AJAX)
$updateUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/danh-gia/cap-nhat-trang-thai';
$ch = curl_init($updateUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['X-Requested-With: XMLHttpRequest'],
    CURLOPT_POSTFIELDS     => http_build_query([
        'review_id'  => 1,
        'trang_thai' => 'hien_thi'
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$updateResp = curl_exec($ch);
$updateCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("POST /admin/danh-gia/cap-nhat-trang-thai returns HTTP 200", $updateCode === 200);
$updateDecoded = json_decode($updateResp, true);
assertTest("Update AJAX returns success JSON", isset($updateDecoded['success']) && $updateDecoded['success'] === true);

if (file_exists($cookieFile)) unlink($cookieFile);

echo "\n========================================================\n";
echo " TEST SUMMARY: Passed: $passed | Failed: $failed\n";
echo "========================================================\n";

if ($failed === 0) {
    echo ">>> ALL REVIEW MODERATION TESTS PASSED 100%! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED! <<<\n";
    exit(1);
}
