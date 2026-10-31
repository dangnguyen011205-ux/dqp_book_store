<?php
// scratch/test_admin_users.php
// Bộ kiểm thử tích hợp tự động cho Nhiệm vụ 7 - Phân hệ Quản Lý Người Dùng & Phân Quyền Admin

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/User.php';

use Models\User;

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
echo " KIỂM THỬ TÍCH HỢP NHIỆM VỤ 7: QUẢN LÝ NGƯỜI DÙNG & PHÂN QUYỀN\n";
echo "========================================================\n\n";

$userModel = new User();

// --- 1. Thống kê KPI Người Dùng ---
echo "--- 1. Kiểm tra thống kê KPI (getStatistics) ---\n";
$stats = $userModel->getStatistics();
assertTest("Trả về mảng thống kê hợp lệ", is_array($stats));
assertTest("Chứa khóa total >= 1", isset($stats['total']) && $stats['total'] >= 1);
assertTest("Chứa khóa khach_hang >= 0", isset($stats['khach_hang']) && $stats['khach_hang'] >= 0);
assertTest("Chứa khóa quan_tri >= 1", isset($stats['quan_tri']) && $stats['quan_tri'] >= 1);
assertTest("Chứa khóa hoat_dong >= 1", isset($stats['hoat_dong']) && $stats['hoat_dong'] >= 1);
assertTest("Chứa khóa khoa >= 0", isset($stats['khoa']) && $stats['khoa'] >= 0);
assertTest("Tổng người dùng khớp với tổng theo vai trò", $stats['total'] === ($stats['khach_hang'] + $stats['quan_tri']));
assertTest("Tổng người dùng khớp với tổng theo trạng thái", $stats['total'] === ($stats['hoat_dong'] + $stats['khoa']));

// --- 2. Lấy Danh Sách & Bộ Lọc ---
echo "\n--- 2. Kiểm tra danh sách và bộ lọc (getAll) ---\n";
$allUsers = $userModel->getAll();
assertTest("Lấy danh sách người dùng không rỗng", !empty($allUsers));
$firstUser = $allUsers[0];
assertTest("Mỗi bản ghi có cột 'so_don_hang'", isset($firstUser['so_don_hang']));
assertTest("Mỗi bản ghi có cột 'tong_chi_tieu'", isset($firstUser['tong_chi_tieu']));
assertTest("Mỗi bản ghi có cột 'vai_tro'", isset($firstUser['vai_tro']));
assertTest("Mỗi bản ghi có cột 'trang_thai'", isset($firstUser['trang_thai']));

// Tìm kiếm theo từ khóa admin
$searchAdmin = $userModel->getAll('admin');
assertTest("Tìm kiếm theo từ khóa 'admin' trả về kết quả", count($searchAdmin) >= 1);
assertTest("Email hoặc tên người dùng chứa 'admin'", str_contains(strtolower($searchAdmin[0]['email']), 'admin') || str_contains(strtolower($searchAdmin[0]['ho_ten']), 'admin'));

// Lọc theo vai trò 'quan_tri'
$admins = $userModel->getAll('', 'quan_tri');
$allAdmins = true;
foreach ($admins as $u) {
    if ($u['vai_tro'] !== 'quan_tri') {
        $allAdmins = false;
        break;
    }
}
assertTest("Lọc theo vai trò 'quan_tri' chỉ trả về quản trị viên", !empty($admins) && $allAdmins);

// Lọc theo vai trò 'khach_hang'
$customers = $userModel->getAll('', 'khach_hang');
$allCustomers = true;
foreach ($customers as $u) {
    if ($u['vai_tro'] !== 'khach_hang') {
        $allCustomers = false;
        break;
    }
}
assertTest("Lọc theo vai trò 'khach_hang' chỉ trả về khách hàng", !empty($customers) && $allCustomers);

// Lọc theo trạng thái 'hoat_dong'
$activeUsers = $userModel->getAll('', '', 'hoat_dong');
$allActive = true;
foreach ($activeUsers as $u) {
    if ($u['trang_thai'] !== 'hoat_dong') {
        $allActive = false;
        break;
    }
}
assertTest("Lọc theo trạng thái 'hoat_dong' chỉ trả về tài khoản hoạt động", !empty($activeUsers) && $allActive);

// --- 3. Chi Tiết Người Dùng Kèm Lịch Sử Đơn Hàng ---
echo "\n--- 3. Kiểm tra chi tiết người dùng (getUserDetailWithOrders) ---\n";
$adminDetail = $userModel->getUserDetailWithOrders(1);
assertTest("Lấy chi tiết người dùng ID 1 hợp lệ", !empty($adminDetail));
assertTest("Chi tiết người dùng không để lộ mat_khau_hash", !isset($adminDetail['mat_khau_hash']));
assertTest("Có mảng dia_chi_list", isset($adminDetail['dia_chi_list']) && is_array($adminDetail['dia_chi_list']));
assertTest("Có mảng don_hang_list", isset($adminDetail['don_hang_list']) && is_array($adminDetail['don_hang_list']));
assertTest("Có trường thống kê tong_don >= 0", isset($adminDetail['tong_don']) && $adminDetail['tong_don'] >= 0);
assertTest("Có trường thống kê tong_chi_tieu >= 0", isset($adminDetail['tong_chi_tieu']) && $adminDetail['tong_chi_tieu'] >= 0);

// --- 4. Tạo Người Dùng Mẫu & Kiểm Thử Thao Tác Nghiệp Vụ ---
echo "\n--- 4. Kiểm thử CRUD, Khóa/Mở khóa & Phân quyền trên User Test ---\n";
$testEmail = 'test_user_' . time() . '@bookstore.vn';
$testUserId = $userModel->create([
    'ho_ten'        => 'Người Dùng Test Tự Động',
    'email'         => $testEmail,
    'mat_khau_hash' => password_hash('123456', PASSWORD_BCRYPT),
    'so_dien_thoai' => '0987654321',
    'vai_tro'       => 'khach_hang',
    'trang_thai'    => 'hoat_dong'
]);
assertTest("Tạo tài khoản người dùng test trả về ID > 0", $testUserId > 0);

// 4.1 Cập nhật thông tin profile
$updateProfileOk = $userModel->updateProfile($testUserId, [
    'ho_ten'        => 'Người Dùng Test (Đã Đổi Tên)',
    'so_dien_thoai' => '0911223344'
]);
assertTest("updateProfile trả về true", $updateProfileOk);
$userAfterUpdate = $userModel->findById($testUserId);
assertTest("Họ tên đã được cập nhật chính xác", $userAfterUpdate['ho_ten'] === 'Người Dùng Test (Đã Đổi Tên)');
assertTest("Số điện thoại đã được cập nhật chính xác", $userAfterUpdate['so_dien_thoai'] === '0911223344');

// 4.2 Khóa tài khoản
$lockOk = $userModel->updateStatus($testUserId, 'khoa');
assertTest("updateStatus('khoa') trả về true", $lockOk);
assertTest("isLocked nhận diện đúng tài khoản bị khóa", $userModel->isLocked($testUserId) === true);

// 4.3 Mở khóa tài khoản
$unlockOk = $userModel->updateStatus($testUserId, 'hoat_dong');
assertTest("updateStatus('hoat_dong') trả về true", $unlockOk);
assertTest("isLocked nhận diện đúng tài khoản đã mở khóa", $userModel->isLocked($testUserId) === false);

// 4.4 Phân quyền lên Quản trị viên
$promoteOk = $userModel->changeRole($testUserId, 'quan_tri');
assertTest("changeRole('quan_tri') trả về true", $promoteOk);
$userAfterPromote = $userModel->findById($testUserId);
assertTest("Vai trò chuyển thành 'quan_tri'", $userAfterPromote['vai_tro'] === 'quan_tri');

// 4.5 Phân quyền về Khách hàng
$demoteOk = $userModel->changeRole($testUserId, 'khach_hang');
assertTest("changeRole('khach_hang') trả về true", $demoteOk);
$userAfterDemote = $userModel->findById($testUserId);
assertTest("Vai trò chuyển về 'khach_hang'", $userAfterDemote['vai_tro'] === 'khach_hang');

// --- 5. Dọn Dẹp Dữ Liệu Test ---
$db = \Core\Database::getInstance()->getConnection();
$db->prepare("DELETE FROM `nguoi_dung` WHERE `id` = ?")->execute([$testUserId]);
assertTest("Đã dọn dẹp sạch tài khoản test khỏi CSDL", $userModel->findById($testUserId) === null);

// --- 6. Kiểm Tra Định Tuyến & Tệp Giao Diện ---
echo "\n--- 6. Kiểm tra định tuyến Route và Views ---\n";
$appContent = file_get_contents(__DIR__ . '/../Core/App.php');
assertTest("Đã định tuyến 'admin/nguoi-dung'", str_contains($appContent, "'admin/nguoi-dung'"));
assertTest("Đã định tuyến 'admin/nguoi-dung/them'", str_contains($appContent, "'admin/nguoi-dung/them'"));
assertTest("Đã định tuyến 'admin/nguoi-dung/khoa/(\d+)'", str_contains($appContent, "'admin/nguoi-dung/khoa/(\\d+)'"));
assertTest("Đã định tuyến 'admin/nguoi-dung/vai-tro/(\d+)'", str_contains($appContent, "'admin/nguoi-dung/vai-tro/(\\d+)'"));
assertTest("Đã định tuyến 'admin/nguoi-dung/chi-tiet/(\d+)'", str_contains($appContent, "'admin/nguoi-dung/chi-tiet/(\\d+)'"));
assertTest("Đã định tuyến 'admin/nguoi-dung/sua/(\d+)'", str_contains($appContent, "'admin/nguoi-dung/sua/(\\d+)'"));

$viewPath = __DIR__ . '/../Views/admin/users/index.php';
assertTest("Tệp Views/admin/users/index.php tồn tại", file_exists($viewPath));
$viewContent = file_get_contents($viewPath);
assertTest("View chứa modal #addUserModal", str_contains($viewContent, 'id="addUserModal"'));
assertTest("View chứa modal #userDetailModal", str_contains($viewContent, 'id="userDetailModal"'));
assertTest("View chứa modal #userEditModal", str_contains($viewContent, 'id="userEditModal"'));
assertTest("View chứa Toast #statusToast", str_contains($viewContent, 'id="statusToast"'));
assertTest("View chứa JavaScript baseUrl có trailing slash", str_contains($viewContent, "const baseUrl = '<?= base_url() ?>/';"));

// --- 7. Kiểm Tra HTTP Endpoints & Ràng Buộc Bảo Mật Qua Apache ---
echo "\n--- 7. Kiểm tra HTTP Endpoints & Ràng Buộc Bảo Mật qua cURL ---\n";
$cookieFile = __DIR__ . '/admin_cookie_user_test.txt';
if (file_exists($cookieFile)) unlink($cookieFile);

// Đăng nhập Admin lấy session cookie (Admin ID 1)
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

// 7.1 GET /admin/nguoi-dung
$userUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung';
$ch = curl_init($userUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile
]);
$htmlUser = curl_exec($ch);
$codeUser = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/nguoi-dung trả về HTTP 200", $codeUser === 200);
assertTest("Trang chứa tiêu đề 'Quản Lý Người Dùng'", str_contains($htmlUser, 'Quản Lý Người Dùng'));
assertTest("Trang chứa danh sách thành viên", str_contains($htmlUser, 'Danh Sách Thành Viên'));

// 7.2 GET /admin/nguoi-dung/chi-tiet/1
$detailUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung/chi-tiet/1';
$ch = curl_init($detailUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$jsonDetail = curl_exec($ch);
$codeDetail = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("GET /admin/nguoi-dung/chi-tiet/1 trả về HTTP 200", $codeDetail === 200);
$detailData = json_decode($jsonDetail, true);
assertTest("Detail JSON có success = true và thông tin admin", isset($detailData['success']) && $detailData['success'] === true && isset($detailData['data']['ho_ten']));

// 7.3 RÀNG BUỘC BẢO MẬT: Admin tự khóa chính mình bị chặn (HTTP 400)
$lockSelfUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung/khoa/1';
$ch = curl_init($lockSelfUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$lockSelfRes = curl_exec($ch);
$lockSelfCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("POST khóa chính mình trả về HTTP 400", $lockSelfCode === 400);
$lockSelfData = json_decode($lockSelfRes, true);
assertTest("Bị chặn tự khóa với success = false", isset($lockSelfData['success']) && $lockSelfData['success'] === false);
assertTest("Thông báo lỗi nêu rõ không thể tự khóa tài khoản", str_contains($lockSelfData['message'] ?? '', 'không thể tự khóa'));

// 7.4 RÀNG BUỘC BẢO MẬT: Admin tự hạ quyền chính mình bị chặn (HTTP 400)
$roleSelfUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung/vai-tro/1';
$ch = curl_init($roleSelfUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$roleSelfRes = curl_exec($ch);
$roleSelfCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("POST đổi vai trò chính mình trả về HTTP 400", $roleSelfCode === 400);
$roleSelfData = json_decode($roleSelfRes, true);
assertTest("Bị chặn tự đổi vai trò với success = false", isset($roleSelfData['success']) && $roleSelfData['success'] === false);
assertTest("Thông báo lỗi nêu rõ không thể tự thay đổi vai trò", str_contains($roleSelfData['message'] ?? '', 'không thể tự thay đổi vai trò'));

// 7.5 Thao tác Khóa và Mở Khóa trên tài khoản thành viên khác (ví dụ: User ID 2)
$toggleUserUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung/khoa/2';
$ch = curl_init($toggleUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$toggleRes = curl_exec($ch);
$toggleCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("Khóa tài khoản thành viên khác trả về HTTP 200", $toggleCode === 200);
$toggleData = json_decode($toggleRes, true);
assertTest("Khóa thành công có success = true", isset($toggleData['success']) && $toggleData['success'] === true);

// Mở khóa lại để giữ nguyên trạng thái ban đầu cho DB
$ch = curl_init($toggleUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$unlockRes = curl_exec($ch);
$unlockCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

assertTest("Mở khóa lại trả về HTTP 200", $unlockCode === 200);
$unlockData = json_decode($unlockRes, true);
assertTest("Mở khóa thành công có success = true", isset($unlockData['success']) && $unlockData['success'] === true);

// 7.6 THÊM NGƯỜI DÙNG: Kiểm tra validation thiếu họ tên (HTTP 400)
$addUserUrl = 'http://localhost/PHP_nang_cao/book_store_online/admin/nguoi-dung/them';
$ch = curl_init($addUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'ho_ten'   => '',
        'email'    => 'test_invalid@bookstore.vn',
        'mat_khau' => '123456'
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$addRes1 = curl_exec($ch);
$addCode1 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
assertTest("Thêm tài khoản thiếu họ tên trả về HTTP 400", $addCode1 === 400);

// 7.7 THÊM NGƯỜI DÙNG: Kiểm tra validation email trùng lặp (HTTP 400)
$ch = curl_init($addUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'ho_ten'   => 'Admin Nhái',
        'email'    => 'admin@bookstore.vn', // Email đã có
        'mat_khau' => '123456'
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$addRes2 = curl_exec($ch);
$addCode2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
assertTest("Thêm tài khoản trùng email trả về HTTP 400", $addCode2 === 400);
$addJson2 = json_decode($addRes2, true);
assertTest("Thông báo lỗi nêu rõ email đã được đăng ký", str_contains($addJson2['message'] ?? '', 'đã được sử dụng'));

// 7.8 THÊM NGƯỜI DÙNG: Kiểm tra validation mật khẩu quá ngắn (HTTP 400)
$ch = curl_init($addUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'ho_ten'   => 'Khách Mới',
        'email'    => 'khachmoi_' . time() . '@bookstore.vn',
        'mat_khau' => '123' // < 6 ký tự
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$addRes3 = curl_exec($ch);
$addCode3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
assertTest("Thêm tài khoản mật khẩu ngắn trả về HTTP 400", $addCode3 === 400);

// 7.9 THÊM NGƯỜI DÙNG: Thêm thành công tài khoản Quản trị viên mới (HTTP 200)
$newAdminEmail = 'sub_admin_' . time() . '@bookstore.vn';
$ch = curl_init($addUserUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'ho_ten'        => 'Quản Trị Viên Phụ',
        'email'         => $newAdminEmail,
        'mat_khau'      => 'admin999',
        'so_dien_thoai' => '0933445566',
        'vai_tro'       => 'quan_tri'
    ]),
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => ['Accept: application/json', 'X-Requested-With: XMLHttpRequest']
]);
$addRes4 = curl_exec($ch);
$addCode4 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
assertTest("Thêm tài khoản Quản trị viên mới trả về HTTP 200", $addCode4 === 200);
$addJson4 = json_decode($addRes4, true);
assertTest("Kết quả có success = true và trả về user_id", isset($addJson4['success']) && $addJson4['success'] === true && isset($addJson4['user_id']));

// Kiểm tra trong DB
$createdUser = $userModel->findByEmail($newAdminEmail);
assertTest("Tìm thấy tài khoản vừa tạo trong CSDL", $createdUser !== null);
assertTest("Tài khoản có vai trò 'quan_tri'", $createdUser['vai_tro'] === 'quan_tri');
assertTest("Mật khẩu được băm Bcrypt chuẩn xác", password_verify('admin999', $createdUser['mat_khau_hash']));

// Dọn dẹp tài khoản vừa tạo
if ($createdUser) {
    $db->prepare("DELETE FROM `nguoi_dung` WHERE `id` = ?")->execute([$createdUser['id']]);
    assertTest("Đã dọn dẹp tài khoản test vừa tạo khỏi DB", $userModel->findByEmail($newAdminEmail) === null);
}

// Dọn dẹp cookie
if (file_exists($cookieFile)) unlink($cookieFile);

echo "\n========================================================\n";
echo " KẾT QUẢ: {$passed} PASSED | {$failed} FAILED\n";
echo "========================================================\n";

if ($failed > 0) {
    exit(1);
}
