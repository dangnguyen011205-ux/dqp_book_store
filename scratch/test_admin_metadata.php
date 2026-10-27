<?php
// scratch/test_admin_metadata.php
// Automated Integration Test Suite for Admin Metadata (Task 5 - Stage 4)

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Author.php';
require_once __DIR__ . '/../Models/Publisher.php';

use Models\Category;
use Models\Author;
use Models\Publisher;
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
echo " TEST SUITE: ADMIN METADATA CRUD (GIAI ĐOẠN 4 - NHIỆM VỤ 5)\n";
echo "========================================================\n\n";

$catModel = new Category();
$authModel = new Author();
$pubModel = new Publisher();
$db = Database::getInstance()->getConnection();

// --- GROUP 1: CATEGORY CRUD & INTEGRITY CONSTRAINTS ---
echo "--- Group 1: Category CRUD & Constraint Protection ---\n";
// 1.1 Tạo thể loại test
$newCatId = $catModel->create(['ten' => 'Thể loại Test Tự Động', 'mo_ta' => 'Mô tả test tự động']);
assertTest("Category create returns valid ID ($newCatId > 0)", $newCatId > 0);

// 1.2 Tìm kiếm thể loại
$searchCat = $catModel->getAll('Test Tự Động');
assertTest("Category search finds newly created category", count($searchCat) > 0 && $searchCat[0]['id'] == $newCatId);

// 1.3 Cập nhật thể loại
$okUpdateCat = $catModel->update($newCatId, ['ten' => 'Thể loại Test Đã Sửa', 'mo_ta' => 'Mô tả đã sửa']);
assertTest("Category update returns true", $okUpdateCat);
$updatedCat = $catModel->findById($newCatId);
assertTest("Category name updated in DB", $updatedCat['ten'] === 'Thể loại Test Đã Sửa');

// 1.4 Kiểm tra chặn xóa thể loại đang có sách (ID 1 có sách)
$delBlocked = $catModel->deleteSafe(1);
assertTest("deleteSafe blocks deletion of category with books", $delBlocked['success'] === false);

// 1.5 Xóa an toàn thể loại rỗng vừa tạo
$delOk = $catModel->deleteSafe($newCatId);
assertTest("deleteSafe deletes empty category", $delOk['success'] === true);
$checkCat = $catModel->findById($newCatId);
assertTest("Deleted category no longer in DB", $checkCat === null);

// --- GROUP 2: AUTHOR CRUD & INTEGRITY CONSTRAINTS ---
echo "\n--- Group 2: Author CRUD & Constraint Protection ---\n";
// 2.1 Tạo tác giả test
$newAuthId = $authModel->create(['ten' => 'Tác giả Test Tự Động', 'tieu_su' => 'Tiểu sử test']);
assertTest("Author create returns valid ID ($newAuthId > 0)", $newAuthId > 0);

// 2.2 Tìm kiếm tác giả
$searchAuth = $authModel->getAll('Test Tự Động');
assertTest("Author search finds newly created author", count($searchAuth) > 0 && $searchAuth[0]['id'] == $newAuthId);

// 2.3 Cập nhật tác giả
$okUpdateAuth = $authModel->update($newAuthId, ['ten' => 'Tác giả Test Đã Sửa', 'tieu_su' => 'Tiểu sử đã sửa']);
assertTest("Author update returns true", $okUpdateAuth);
$updatedAuth = $authModel->findById($newAuthId);
assertTest("Author name updated in DB", $updatedAuth['ten'] === 'Tác giả Test Đã Sửa');

// 2.4 Kiểm tra chặn xóa tác giả đang có sách (ID 1 có sách)
$delAuthBlocked = $authModel->deleteSafe(1);
assertTest("deleteSafe blocks deletion of author with books", $delAuthBlocked['success'] === false);

// 2.5 Xóa tác giả rỗng vừa tạo
$delAuthOk = $authModel->deleteSafe($newAuthId);
assertTest("deleteSafe deletes empty author", $delAuthOk['success'] === true);
$checkAuth = $authModel->findById($newAuthId);
assertTest("Deleted author no longer in DB", $checkAuth === null);

// --- GROUP 3: PUBLISHER CRUD & INTEGRITY CONSTRAINTS ---
echo "\n--- Group 3: Publisher CRUD & Constraint Protection ---\n";
// 3.1 Tạo NXB test
$newPubId = $pubModel->create(['ten' => 'NXB Test Tự Động', 'dia_chi' => '123 Đường Test, Hà Nội', 'lien_he' => 'test@nxb.vn']);
assertTest("Publisher create returns valid ID ($newPubId > 0)", $newPubId > 0);

// 3.2 Tìm kiếm NXB
$searchPub = $pubModel->getAll('Test Tự Động');
assertTest("Publisher search finds newly created publisher", count($searchPub) > 0 && $searchPub[0]['id'] == $newPubId);

// 3.3 Cập nhật NXB
$okUpdatePub = $pubModel->update($newPubId, ['ten' => 'NXB Test Đã Sửa', 'dia_chi' => '456 Đường Sửa', 'lien_he' => 'sua@nxb.vn']);
assertTest("Publisher update returns true", $okUpdatePub);
$updatedPub = $pubModel->findById($newPubId);
assertTest("Publisher name updated in DB", $updatedPub['ten'] === 'NXB Test Đã Sửa');

// 3.4 Kiểm tra chặn xóa NXB đang có sách (ID 1 có sách)
$delPubBlocked = $pubModel->deleteSafe(1);
assertTest("deleteSafe blocks deletion of publisher with books", $delPubBlocked['success'] === false);

// 3.5 Xóa NXB rỗng vừa tạo
$delPubOk = $pubModel->deleteSafe($newPubId);
assertTest("deleteSafe deletes empty publisher", $delPubOk['success'] === true);
$checkPub = $pubModel->findById($newPubId);
assertTest("Deleted publisher no longer in DB", $checkPub === null);

// --- GROUP 4: HTTP ENDPOINTS & ROUTER ---
echo "\n--- Group 4: HTTP Endpoints & Router Mapping ---\n";
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

// 4.1 GET /admin/the-loai
$catUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/the-loai';
$ch = curl_init($catUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$htmlCat = curl_exec($ch);
$codeCat = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/the-loai returns HTTP 200", $codeCat === 200);
assertTest("Page contains 'Quản Lý Thể Loại Sách' title", strpos($htmlCat, 'Quản Lý Thể Loại Sách') !== false);
assertTest("Page contains category modal #categoryModal", strpos($htmlCat, 'id="categoryModal"') !== false);

// 4.2 GET /admin/the-loai/chi-tiet/1
$catDetailUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/the-loai/chi-tiet/1';
$ch = curl_init($catDetailUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$jsonCat = curl_exec($ch);
$codeCatDetail = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/the-loai/chi-tiet/1 returns HTTP 200", $codeCatDetail === 200);
$catDetailDecoded = json_decode($jsonCat, true);
assertTest("Detail JSON has 'success' => true and category 'ten'", isset($catDetailDecoded['success']) && $catDetailDecoded['success'] === true && !empty($catDetailDecoded['data']['ten']));

// 4.3 GET /admin/tac-gia
$authUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/tac-gia';
$ch = curl_init($authUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$htmlAuth = curl_exec($ch);
$codeAuth = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/tac-gia returns HTTP 200", $codeAuth === 200);
assertTest("Page contains 'Quản Lý Tác Giả' title", strpos($htmlAuth, 'Quản Lý Tác Giả') !== false);
assertTest("Page contains author modal #authorModal", strpos($htmlAuth, 'id="authorModal"') !== false);

// 4.4 GET /admin/tac-gia/chi-tiet/1
$authDetailUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/tac-gia/chi-tiet/1';
$ch = curl_init($authDetailUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$jsonAuth = curl_exec($ch);
$codeAuthDetail = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/tac-gia/chi-tiet/1 returns HTTP 200", $codeAuthDetail === 200);
$authDetailDecoded = json_decode($jsonAuth, true);
assertTest("Detail JSON has 'success' => true and author 'ten'", isset($authDetailDecoded['success']) && $authDetailDecoded['success'] === true && !empty($authDetailDecoded['data']['ten']));

// 4.5 GET /admin/nha-xuat-ban
$pubUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nha-xuat-ban';
$ch = curl_init($pubUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$htmlPub = curl_exec($ch);
$codePub = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/nha-xuat-ban returns HTTP 200", $codePub === 200);
assertTest("Page contains 'Quản Lý Nhà Xuất Bản' title", strpos($htmlPub, 'Quản Lý Nhà Xuất Bản') !== false);
assertTest("Page contains publisher modal #publisherModal", strpos($htmlPub, 'id="publisherModal"') !== false);

// 4.6 GET /admin/nha-xuat-ban/chi-tiet/1
$pubDetailUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nha-xuat-ban/chi-tiet/1';
$ch = curl_init($pubDetailUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$jsonPub = curl_exec($ch);
$codePubDetail = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/nha-xuat-ban/chi-tiet/1 returns HTTP 200", $codePubDetail === 200);
$pubDetailDecoded = json_decode($jsonPub, true);
assertTest("Detail JSON has 'success' => true and publisher 'ten'", isset($pubDetailDecoded['success']) && $pubDetailDecoded['success'] === true && !empty($pubDetailDecoded['data']['ten']));

if (file_exists($cookieFile)) unlink($cookieFile);

echo "\n========================================================\n";
echo " TEST SUMMARY: Passed: $passed | Failed: $failed\n";
echo "========================================================\n";

if ($failed === 0) {
    echo ">>> ALL METADATA INTEGRATION TESTS PASSED 100%! <<<\n";
    exit(0);
} else {
    echo ">>> SOME TESTS FAILED! <<<\n";
    exit(1);
}
