<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Advertisement.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Cart.php';

use Models\Advertisement;
use Models\Coupon;
use Models\Order;

function assertTest($description, $condition) {
    if ($condition) {
        echo "✅ PASS: $description\n";
    } else {
        echo "❌ FAIL: $description\n";
        exit(1);
    }
}

echo "=== KIỂM THỬ TOÀN DIỆN HÀNH VI MỚI CỦA VOUCHER VÀ QUẢNG CÁO ===\n\n";

$adModel = new Advertisement();
$couponModel = new Coupon();
$orderModel = new Order();
$pdo = Core\Database::getInstance()->getConnection();

$userA = 2; // nguyenvanan@gmail.com
$userB = 19; // s@1.com

// 1. Dọn sạch voucher trong túi của User A và User B
$pdo->prepare("DELETE FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` IN (?, ?)")->execute([$userA, $userB]);

$walletA = $couponModel->getUserWalletCoupons($userA, 'available');
$walletB = $couponModel->getUserWalletCoupons($userB, 'available');
assertTest("Túi voucher của User A ban đầu hoàn toàn trống (0 voucher)", count($walletA) === 0);
assertTest("Túi voucher của User B ban đầu hoàn toàn trống (0 voucher)", count($walletB) === 0);

// 2. Thiết lập Quảng cáo 1 liên kết với voucher WELCOME2026 (id=1), giới hạn so_luot_luu_toi_da = 1
$coupon = $couponModel->findByCode('WELCOME2026');
$couponId = (int)$coupon['id'];
$pdo->prepare("UPDATE `quang_cao` SET `ma_giam_gia_id` = ?, `so_luot_luu_toi_da` = 1, `trang_thai` = 1 WHERE `id` = 1")->execute([$couponId]);
// Đảm bảo không còn ai lưu couponId này
$pdo->prepare("DELETE FROM `voucher_nguoi_dung` WHERE `ma_giam_gia_id` = ?")->execute([$couponId]);

// Kiểm tra trạng thái hiển thị cho User A khi chưa lưu và chưa hết lượt
$ads = $adModel->getActiveAds();
$ad = null;
foreach ($ads as $a) {
    if ((int)$a['id'] === 1) { $ad = $a; break; }
}
assertTest("Quảng cáo 1 tồn tại và đang chạy", $ad !== null);
$maxSaves = (int)$ad['so_luot_luu_toi_da'];
$cntSaved = (int)$ad['so_luot_da_luu'];
$isSavedA = in_array($couponId, array_column($adModel->getUserSavedVouchers($userA), 'ma_giam_gia_id'), true);
$isFull = ($maxSaves > 0 && $cntSaved >= $maxSaves);

assertTest("User A chưa lưu voucher", $isSavedA === false);
assertTest("Chương trình chưa đầy (0/1 lượt)", $isFull === false);
// => Nút hiển thị sẽ là 'Lưu Nhanh'

// 3. User A bấm Lưu Nhanh
$saveResA = $adModel->saveUserVoucher($userA, $couponId);
assertTest("User A bấm Lưu Nhanh thành công", $saveResA['success'] === true);

// Bây giờ User A kiểm tra: túi voucher của User A đã có 1 mã khả dụng
$walletA = $couponModel->getUserWalletCoupons($userA, 'available');
assertTest("User A đã có đúng 1 voucher WELCOME2026 trong kho khả dụng", count($walletA) === 1 && $walletA[0]['ma'] === 'WELCOME2026');

// Kiểm tra lại trạng thái cho User A: đã lưu
$savedIdsA = array_column($adModel->getUserSavedVouchers($userA), 'ma_giam_gia_id');
assertTest("User A hiển thị trạng thái 'Đã Lưu'", in_array($couponId, $savedIdsA, true) === true);

// 4. Kiểm tra trạng thái cho User B:
// User B CHƯA LƯU, NHƯNG chương trình đã đạt giới hạn 1/1 lượt
$ads = $adModel->getActiveAds();
foreach ($ads as $a) { if ((int)$a['id'] === 1) { $ad = $a; break; } }
$maxSaves = (int)$ad['so_luot_luu_toi_da'];
$cntSaved = (int)$ad['so_luot_da_luu'];
$isSavedB = in_array($couponId, array_column($adModel->getUserSavedVouchers($userB), 'ma_giam_gia_id'), true);
$isFull = ($maxSaves > 0 && $cntSaved >= $maxSaves);

assertTest("User B chưa lưu voucher", $isSavedB === false);
assertTest("Chương trình đã đạt giới hạn tối đa (1/1)", $isFull === true);
// => Nút cho User B PHẢI hiển thị 'Đã Hết Lượt' (chứ không auto hiện 'Đã Lưu')

// User B cố gắng lưu -> Phải bị chặn
$saveResB = $adModel->saveUserVoucher($userB, $couponId);
assertTest("User B bị chặn không thể lưu vì đã hết lượt", $saveResB['success'] === false && ($saveResB['is_full'] ?? false) === true);

// 5. User A sử dụng voucher WELCOME2026 trong đơn hàng
$subtotal = 200000;
$valA = $couponModel->validateCoupon('WELCOME2026', $subtotal, $userA);
assertTest("User A validate voucher hợp lệ", $valA['valid'] === true);

$orderData = [
    'nguoi_dung_id'   => $userA,
    'nguoi_nhan'      => 'Độc giả A (0987654321)',
    'dia_chi_giao'    => 'TP.HCM',
    'tong_tien_hang'  => $subtotal,
    'tien_giam'       => (float)$valA['discount'],
    'phi_van_chuyen'  => 0,
    'tong_thanh_toan' => $subtotal - (float)$valA['discount'],
    'phuong_thuc'     => 'COD',
    'ghi_chu_khach'   => 'Test used voucher disappearance'
];
$cartItems = [['sach_id' => 1, 'so_luong' => 1, 'gia' => 200000]];

$orderRes = $orderModel->createOrder($orderData, $cartItems, $valA['coupon']);
assertTest("User A đặt hàng thành công", $orderRes['success'] === true);
$createdOrderId = (int)$orderRes['order_id'];

// 6. KIỂM TRA: VOUCHER ĐÃ DÙNG PHẢI BIẾN MẤT KHỎI KHO VOUCHER KHẢ DỤNG
$walletAfterOrder = $couponModel->getUserWalletCoupons($userA, 'available');
assertTest("Voucher đã dùng BIẾN MẤT HOÀN TOÀN khỏi Kho Voucher khả dụng (0 voucher)", count($walletAfterOrder) === 0);

// 7. KIỂM TRA: VOUCHER ĐÃ DÙNG XUẤT HIỆN Ở MỤC 'VOUCHER ĐÃ SỬ DỤNG' VÀ CÓ ID ĐƠN HÀNG
$usedCoupons = $couponModel->getUsedCouponsWithOrders($userA);
assertTest("Mục 'Voucher Đã Sử Dụng' có đúng 1 bản ghi", count($usedCoupons) === 1);
assertTest("Bản ghi có mã WELCOME2026", $usedCoupons[0]['ma'] === 'WELCOME2026');
assertTest("Bản ghi liên kết đúng mã đơn hàng #BK-$createdOrderId", (int)$usedCoupons[0]['don_hang_id'] === $createdOrderId);
assertTest("Bản ghi có thông tin tiền đã giảm", (float)$usedCoupons[0]['so_tien_da_giam'] > 0);

echo "\n🎉 TẤT CẢ TEST ĐÃ HOÀN TẤT THÀNH CÔNG 100%!\n";
