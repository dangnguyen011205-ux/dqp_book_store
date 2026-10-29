<?php
// scratch/test_admin_coupons.php
// Bộ kiểm thử tích hợp tự động cho Nhiệm vụ 6 - Phân hệ Quản lý Mã Giảm Giá Admin

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Coupon.php';

use Models\Coupon;

$passed = 0;
$failed = 0;

function assertTest(string $desc, bool $condition) {
    global $passed, $failed;
    if ($condition) {
        echo "  [PASS] {$desc}\n";
        $passed++;
    } else {
        echo "  [FAIL] {$desc}\n";
        $failed++;
    }
}

echo "========================================================\n";
echo " KIỂM THỬ TÍCH HỢP NHIỆM VỤ 6: QUẢN LÝ MÃ GIẢM GIÁ ADMIN\n";
echo "========================================================\n\n";

$couponModel = new Coupon();

// Test 1: Thống kê KPI mã giảm giá
echo "--- 1. Kiểm tra thống kê KPI (getStatistics) ---\n";
$stats = $couponModel->getStatistics();
assertTest("Trả về mảng thống kê hợp lệ", is_array($stats));
assertTest("Chứa khóa total >= 0", isset($stats['total']) && $stats['total'] >= 0);
assertTest("Chứa khóa active >= 0", isset($stats['active']) && $stats['active'] >= 0);
assertTest("Chứa khóa expired >= 0", isset($stats['expired']) && $stats['expired'] >= 0);
assertTest("Chứa khóa max_used >= 0", isset($stats['max_used']) && $stats['max_used'] >= 0);
assertTest("Chứa khóa total_used >= 0", isset($stats['total_used']) && $stats['total_used'] >= 0);
assertTest("Chứa khóa total_discount >= 0", isset($stats['total_discount']) && $stats['total_discount'] >= 0);

// Test 2: Lấy danh sách mã giảm giá kèm bộ lọc (getAll)
echo "\n--- 2. Kiểm tra danh sách và bộ lọc (getAll) ---\n";
$allCoupons = $couponModel->getAll();
assertTest("Lấy danh sách mã giảm giá không rỗng", !empty($allCoupons));
$firstCoupon = $allCoupons[0];
assertTest("Mỗi bản ghi có cột 'trang_thai_hieu_luc'", isset($firstCoupon['trang_thai_hieu_luc']));
assertTest("Mỗi bản ghi có cột 'so_don_hang'", isset($firstCoupon['so_don_hang']));

// Lọc theo search
$welcomeCoupons = $couponModel->getAll('WELCOME');
assertTest("Tìm kiếm theo mã 'WELCOME' trả về ít nhất 1 kết quả", count($welcomeCoupons) >= 1);
assertTest("Mã tìm được chứa WELCOME", str_contains($welcomeCoupons[0]['ma'], 'WELCOME'));

// Lọc theo loại 'phan_tram'
$percentCoupons = $couponModel->getAll('', '', 'phan_tram');
$allPercent = true;
foreach ($percentCoupons as $c) {
    if ($c['loai'] !== 'phan_tram') $allPercent = false;
}
assertTest("Lọc theo loại 'phan_tram' trả về đúng loại", $allPercent && count($percentCoupons) >= 1);

// Lọc theo loại 'tien_mat'
$cashCoupons = $couponModel->getAll('', '', 'tien_mat');
$allCash = true;
foreach ($cashCoupons as $c) {
    if ($c['loai'] !== 'tien_mat') $allCash = false;
}
assertTest("Lọc theo loại 'tien_mat' trả về đúng loại", $allCash && count($cashCoupons) >= 1);

// Test 3: Kiểm tra tính duy nhất của mã (checkCodeExists)
echo "\n--- 3. Kiểm tra tính duy nhất của mã code (checkCodeExists) ---\n";
assertTest("Mã 'WELCOME2026' đã tồn tại", $couponModel->checkCodeExists('WELCOME2026'));
assertTest("Mã 'welcome2026' (chữ thường) cũng nhận diện đã tồn tại", $couponModel->checkCodeExists('welcome2026'));
assertTest("Mã chưa từng có 'NON_EXISTENT_9999' trả về false", !$couponModel->checkCodeExists('NON_EXISTENT_9999'));

// Test 4: Thêm mới mã giảm giá (create)
echo "\n--- 4. Kiểm tra thêm mới mã giảm giá (create) ---\n";
$testCode = 'TEST_AUTOPROMO_' . time();
$newId = $couponModel->create([
    'ma'            => $testCode,
    'loai'          => 'phan_tram',
    'gia_tri'       => 20,
    'don_toi_thieu' => 100000,
    'bat_dau'       => date('Y-m-d H:i:s', strtotime('-1 day')),
    'ket_thuc'      => date('Y-m-d H:i:s', strtotime('+30 days')),
    'so_luot_toi_da'=> 50
]);
assertTest("Tạo mã giảm giá mới trả về ID > 0", $newId > 0);

// Test 5: Tìm theo ID (findById)
echo "\n--- 5. Kiểm tra chi tiết mã giảm giá (findById) ---\n";
$createdCoupon = $couponModel->findById($newId);
assertTest("Tìm thấy mã vừa tạo qua findById", $createdCoupon !== null);
assertTest("Mã được tự động chuẩn hóa chữ HOA", $createdCoupon['ma'] === strtoupper($testCode));
assertTest("Loại giảm giá là 'phan_tram'", $createdCoupon['loai'] === 'phan_tram');
assertTest("Giá trị là 20.00", (float)$createdCoupon['gia_tri'] === 20.0);
assertTest("Đơn tối thiểu là 100000.00", (float)$createdCoupon['don_toi_thieu'] === 100000.0);
assertTest("Số lượt tối đa là 50", (int)$createdCoupon['so_luot_toi_da'] === 50);
assertTest("Số lượt đã dùng ban đầu là 0", (int)$createdCoupon['da_dung'] === 0);

// Test 6: Cập nhật mã giảm giá (update)
echo "\n--- 6. Kiểm tra cập nhật mã giảm giá (update) ---\n";
$updateResult = $couponModel->update($newId, [
    'ma'            => $testCode . '_MOD',
    'loai'          => 'tien_mat',
    'gia_tri'       => 50000,
    'don_toi_thieu' => 250000,
    'bat_dau'       => date('Y-m-d H:i:s', strtotime('-2 days')),
    'ket_thuc'      => date('Y-m-d H:i:s', strtotime('+60 days')),
    'so_luot_toi_da'=> 150
]);
assertTest("Cập nhật thành công trả về true", $updateResult === true);
$updatedCoupon = $couponModel->findById($newId);
assertTest("Mã sau cập nhật có đuôi _MOD", str_ends_with($updatedCoupon['ma'], '_MOD'));
assertTest("Loại sau cập nhật chuyển thành 'tien_mat'", $updatedCoupon['loai'] === 'tien_mat');
assertTest("Giá trị sau cập nhật là 50000.00", (float)$updatedCoupon['gia_tri'] === 50000.0);
assertTest("Số lượt tối đa sau cập nhật là 150", (int)$updatedCoupon['so_luot_toi_da'] === 150);

// Test 7: checkCodeExists loại trừ ID chính nó
echo "\n--- 7. Kiểm tra trùng lặp mã khi chỉnh sửa ---\n";
assertTest("Không báo trùng với chính ID của voucher đang sửa", !$couponModel->checkCodeExists($updatedCoupon['ma'], $newId));
assertTest("Báo trùng nếu mã thuộc voucher khác", $couponModel->checkCodeExists('WELCOME2026', $newId));

// Test 8: Xóa mã giảm giá (delete)
echo "\n--- 8. Kiểm tra xóa mã giảm giá (delete) ---\n";
$deleteResult = $couponModel->delete($newId);
assertTest("Xóa thành công trả về success = true", $deleteResult['success'] === true);
$deletedCoupon = $couponModel->findById($newId);
assertTest("Không còn tìm thấy mã sau khi xóa", $deletedCoupon === null);

// Test 9: Kiểm tra Route & Controller App.php
echo "\n--- 9. Kiểm tra định tuyến Route trong Core/App.php ---\n";
$appFile = file_get_contents(__DIR__ . '/../Core/App.php');
assertTest("Đã có route 'admin/ma-giam-gia'", str_contains($appFile, "'admin/ma-giam-gia'"));
assertTest("Đã có route 'admin/ma-giam-gia/them'", str_contains($appFile, "'admin/ma-giam-gia/them'"));
assertTest("Đã có route 'admin/ma-giam-gia/chi-tiet/(\d+)'", str_contains($appFile, "'admin/ma-giam-gia/chi-tiet/(\\d+)'"));
assertTest("Đã có route 'admin/ma-giam-gia/sua/(\d+)'", str_contains($appFile, "'admin/ma-giam-gia/sua/(\\d+)'"));
assertTest("Đã có route 'admin/ma-giam-gia/xoa/(\d+)'", str_contains($appFile, "'admin/ma-giam-gia/xoa/(\\d+)'"));

// Test 10: Kiểm tra tồn tại View index.php
echo "\n--- 10. Kiểm tra tệp View admin/coupons/index.php ---\n";
$viewPath = __DIR__ . '/../Views/admin/coupons/index.php';
assertTest("Tệp Views/admin/coupons/index.php tồn tại", file_exists($viewPath));
$viewContent = file_get_contents($viewPath);
assertTest("View chứa modal #couponModal", str_contains($viewContent, 'id="couponModal"'));
assertTest("View chứa form #couponForm", str_contains($viewContent, 'id="couponForm"'));
assertTest("View chứa Toast #statusToast", str_contains($viewContent, 'id="statusToast"'));
assertTest("View chứa JavaScript baseUrl có trailing slash", str_contains($viewContent, "const baseUrl = '<?= base_url() ?>/';"));

echo "\n========================================================\n";
echo " KẾT QUẢ: {$passed} PASSED | {$failed} FAILED\n";
echo "========================================================\n";

