<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Book.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Author.php';
require_once __DIR__ . '/../Models/Publisher.php';
require_once __DIR__ . '/../Models/InventorySlip.php';

session_start();

echo "========================================================\n";
echo " KIỂM THỬ TỰ ĐỘNG NHIỆM VỤ 2: ADMIN BOOKS & INVENTORY\n";
echo "========================================================\n\n";

$pdo = \Core\Database::getInstance()->getConnection();
$bookModel = new \Models\Book();
$slipModel = new \Models\InventorySlip();

$pass = 0;
$fail = 0;

function assertTest(string $name, bool $condition, string $detail = '') {
    global $pass, $fail;
    if ($condition) {
        echo "[PASS] $name\n";
        $pass++;
    } else {
        echo "[FAIL] $name" . ($detail ? " ($detail)" : "") . "\n";
        $fail++;
    }
}

// 1. Test getAdminStats
$stats = $bookModel->getAdminStats();
assertTest("1. Book::getAdminStats() trả về dữ liệu KPI", 
    isset($stats['total_titles']) && isset($stats['total_stock']) && isset($stats['low_stock_count']) && isset($stats['estimated_stock_value']),
    print_r($stats, true)
);

// 2. Test getAdminBooks
$booksData = $bookModel->getAdminBooks([], 1, 10);
assertTest("2. Book::getAdminBooks() trả về danh sách có phân trang", 
    isset($booksData['data']) && isset($booksData['total']) && count($booksData['data']) > 0
);

// 3. Test Tạo Sách Mới (createBook)
$testBookTitle = 'Sách Test Nghiệp Vụ ' . time();
$newBookId = $bookModel->createBook([
    'tieu_de'      => $testBookTitle,
    'gia'          => 125000,
    'mo_ta'        => 'Mô tả thử nghiệm kiểm thử tự động',
    'anh_bia'      => 'test_cover.jpg',
    'so_trang'     => 250,
    'nam_xuat_ban' => 2026,
    'so_luong_ton' => 50,
    'the_loai_id'  => 1,
    'nxb_id'       => 1,
    'dang_ban'     => 1,
    'user_id'      => 1
], [1, 2]);

assertTest("3. Book::createBook() tạo tựa sách thành công", $newBookId > 0, "ID: $newBookId");

// 4. Test getBookForEdit
$editData = $bookModel->getBookForEdit($newBookId);
assertTest("4. Book::getBookForEdit() lấy dữ liệu sách và danh sách tác giả",
    $editData && $editData['tieu_de'] === $testBookTitle && count($editData['author_ids']) === 2
);

// 5. Test Cập nhật sách (updateBook)
$updatedTitle = $testBookTitle . ' (Đã cập nhật)';
$updateOk = $bookModel->updateBook($newBookId, [
    'tieu_de'      => $updatedTitle,
    'gia'          => 139000,
    'mo_ta'        => 'Mô tả cập nhật',
    'so_trang'     => 260,
    'nam_xuat_ban' => 2026,
    'the_loai_id'  => 2,
    'nxb_id'       => 2,
    'dang_ban'     => 1
], [1]);
$refetched = $bookModel->getBookForEdit($newBookId);
assertTest("5. Book::updateBook() cập nhật thông tin và sync tác giả",
    $updateOk && $refetched['tieu_de'] === $updatedTitle && count($refetched['author_ids']) === 1
);

// 6. Test toggleStatus
$bookModel->toggleStatus($newBookId);
$statusAfter = (int)$pdo->query("SELECT dang_ban FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("6. Book::toggleStatus() chuyển trạng thái sang 0 (Ngừng bán)", $statusAfter === 0);
$bookModel->toggleStatus($newBookId);
$statusAfter2 = (int)$pdo->query("SELECT dang_ban FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("7. Book::toggleStatus() chuyển trạng thái lại sang 1 (Đang bán)", $statusAfter2 === 1);

// 8. Test Inventory Slip Transaction - Nhập kho (+30)
$initialStock = (int)$pdo->query("SELECT so_luong_ton FROM sach WHERE id = $newBookId")->fetchColumn();
$slipIn = $slipModel->createSlip($newBookId, 1, 'nhap', 30, 'Nhập bổ sung kiểm thử');
$stockAfterIn = (int)$pdo->query("SELECT so_luong_ton FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("8. InventorySlip::createSlip('nhap') tăng tồn kho đúng số lượng (+30)",
    $slipIn['success'] && $stockAfterIn === ($initialStock + 30)
);

// 9. Test Inventory Slip Transaction - Xuất kho vượt quá tồn kho (Bị từ chối an toàn)
$slipOutOver = $slipModel->createSlip($newBookId, 1, 'xuat', $stockAfterIn + 500, 'Xuất lố tồn');
$stockAfterOver = (int)$pdo->query("SELECT so_luong_ton FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("9. InventorySlip::createSlip('xuat') từ chối khi xuất quá số tồn kho",
    !$slipOutOver['success'] && $stockAfterOver === $stockAfterIn
);

// 10. Test Inventory Slip Transaction - Xuất kho hợp lệ (-15)
$slipOut = $slipModel->createSlip($newBookId, 1, 'xuat', 15, 'Xuất kho hợp lệ');
$stockAfterOut = (int)$pdo->query("SELECT so_luong_ton FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("10. InventorySlip::createSlip('xuat') trừ tồn kho chính xác (-15)",
    $slipOut['success'] && $stockAfterOut === ($stockAfterIn - 15)
);

// 11. Test Inventory Slip Transaction - Điều chỉnh giảm (-5)
$slipAdjust = $slipModel->createSlip($newBookId, 1, 'dieu_chinh', 5, 'Kiểm kê hư hại', 'giam');
$stockAfterAdjust = (int)$pdo->query("SELECT so_luong_ton FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("11. InventorySlip::createSlip('dieu_chinh') giảm tồn kho chính xác (-5)",
    $slipAdjust['success'] && $stockAfterAdjust === ($stockAfterOut - 5)
);

// 12. Test Inventory Slip Query
$slips = $slipModel->getAll(10, 0, null, $newBookId);
assertTest("12. InventorySlip::getAll() tìm thấy các phiếu vừa tạo của sách",
    count($slips) >= 3
);

// 13. Test Xóa sách (deleteBook)
$deleteResult = $bookModel->deleteBook($newBookId);
$bookExists = (int)$pdo->query("SELECT COUNT(*) FROM sach WHERE id = $newBookId")->fetchColumn();
assertTest("13. Book::deleteBook() dọn sạch tựa sách kiểm thử",
    $deleteResult['action'] === 'deleted' && $bookExists === 0
);

// 14. Test HTTP Endpoints via cURL with Admin Session Cookie
$adminCookie = tempnam(sys_get_temp_dir(), 'admin_cookie_');
$loginUrl = 'http://localhost/PHP_nang_cao/book_store_online/dang-nhap';

// Đăng nhập admin
$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email'    => 'admin@bookstore.vn',
    'password' => '123456'
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $adminCookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, $adminCookie);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);

// GET /admin/sach
$ch = curl_init('http://localhost/PHP_nang_cao/book_store_online/admin/sach');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $adminCookie);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$hasTitle = strpos($response, 'Quản Lý Sách &amp; Tồn Kho') !== false || strpos($response, 'Quản Lý Sách & Tồn Kho') !== false;
$hasKpi = strpos($response, 'Tổng số đầu sách') !== false;
$hasAddModal = strpos($response, 'id="modal-add-book"') !== false;
$hasEditModal = strpos($response, 'id="modal-edit-book"') !== false;
$hasQuickSlipModal = strpos($response, 'id="modal-quick-slip"') !== false;

assertTest("14. HTTP GET /admin/sach trả về 200 OK và chứa đủ các thành phần UI",
    $httpCode === 200 && $hasTitle && $hasKpi && $hasAddModal && $hasEditModal && $hasQuickSlipModal,
    "HTTP: $httpCode"
);

// GET /admin/kho
$ch = curl_init('http://localhost/PHP_nang_cao/book_store_online/admin/kho');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $adminCookie);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$responseKho = curl_exec($ch);
$httpCodeKho = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("15. HTTP GET /admin/kho chuyển hướng và hiển thị phân hệ phiếu kho",
    $httpCodeKho === 200 && (strpos($responseKho, 'tab-slips-content') !== false)
);

// GET /admin/sach/sua/1?json=1
$ch = curl_init('http://localhost/PHP_nang_cao/book_store_online/admin/sach/sua/1?json=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $adminCookie);
$responseJson = curl_exec($ch);
$httpCodeJson = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$jsonObj = json_decode($responseJson, true);

assertTest("16. HTTP GET /admin/sach/sua/1?json=1 trả về JSON hợp lệ cho Modal chỉnh sửa",
    $httpCodeJson === 200 && is_array($jsonObj) && isset($jsonObj['tieu_de']) && isset($jsonObj['author_ids'])
);

@unlink($adminCookie);

echo "\n========================================================\n";
echo " KẾT QUẢ KIỂM THỬ: $pass PASSED / $fail FAILED\n";
echo "========================================================\n";

if ($fail > 0) {
    exit(1);
}
