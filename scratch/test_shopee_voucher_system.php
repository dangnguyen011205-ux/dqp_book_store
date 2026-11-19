<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Core/Helper.php';
require_once __DIR__ . '/../Models/Coupon.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/Book.php';
require_once __DIR__ . '/../Models/Cart.php';

use Models\Coupon;
use Models\Order;
use Models\Book;

echo "=== BẮT ĐẦU KIỂM THỬ HỆ THỐNG VOUCHER CHUẨN THƯƠNG MẠI ĐIỆN TỬ (SHOPEE STYLE) ===\n\n";

class TestHelper extends Core\Model {
    public function runSql(string $sql, array $params = []) {
        return $this->execute($sql, $params);
    }
    public function getOne(string $sql, array $params = []) {
        return $this->fetchOne($sql, $params);
    }
}
$helper = new TestHelper();
$couponModel = new Coupon();
$orderModel = new Order();
$bookModel = new Book();

// 1. Kiểm tra cấu hình và phân loại voucher
echo "--- 1. Kiểm tra phân loại voucher và mức giảm tối đa (giam_toi_da) ---\n";
$welcome = $couponModel->findByCode('WELCOME2026');
assert($welcome !== null, "Tìm thấy WELCOME2026");
assert($welcome['loai_voucher'] === 'giam_gia', "WELCOME2026 là loại giảm giá đơn hàng");
assert((float)$welcome['giam_toi_da'] === 50000.0, "WELCOME2026 có mức giảm tối đa 50k");
echo "✅ PASS: WELCOME2026 phân loại đúng 'giam_gia' và có trần giảm tối đa 50.000đ\n";

$freeship = $couponModel->findByCode('FREESHIP');
assert($freeship !== null, "Tìm thấy FREESHIP");
assert($freeship['loai_voucher'] === 'mien_phi_van_chuyen', "FREESHIP là loại freeship");
echo "✅ PASS: FREESHIP phân loại đúng 'mien_phi_van_chuyen'\n";

// 2. Kiểm tra tính toán giảm giá có trần (giam_toi_da) và giới hạn phí ship
echo "\n--- 2. Kiểm tra tính toán giảm giá chuẩn E-commerce ---\n";
// WELCOME2026: 10% tối đa 50k trên đơn 800.000đ -> 10% của 800k = 80k -> Capped at 50k!
$calcWelcome = $couponModel->validateCoupon('WELCOME2026', 800000.0, null, 22000.0);
assert($calcWelcome['valid'] === true, "WELCOME2026 hợp lệ cho đơn 800k");
assert((float)$calcWelcome['discount'] === 50000.0, "10% của 800k (80k) bị giới hạn trần tối đa đúng 50.000đ");
echo "✅ PASS: Giảm % đơn hàng áp dụng trần giam_toi_da chính xác ({$calcWelcome['discount']}đ)\n";

// FREESHIP: 25k trên phí ship 22k -> Không thể giảm vượt quá 22k!
$calcFreeship = $couponModel->validateCoupon('FREESHIP', 150000.0, null, 22000.0);
assert($calcFreeship['valid'] === true, "FREESHIP hợp lệ cho đơn 150k");
assert((float)$calcFreeship['discount'] === 22000.0, "Mức giảm freeship không vượt quá phí ship thực tế (22.000đ)");
echo "✅ PASS: Mã Freeship tự động giới hạn bằng phí ship thực tế ({$calcFreeship['discount']}đ)\n";

// 3. Kiểm tra tính năng Lưu Voucher bằng mã code (Claim voucher to wallet)
echo "\n--- 3. Kiểm tra tính năng Nhập mã lưu voucher vào ví (Shopee Claim) ---\n";
$userRow = $helper->getOne("SELECT id FROM `nguoi_dung` ORDER BY id ASC LIMIT 1");
$testUserId = (int)($userRow['id'] ?? 1);
$helper->runSql("DELETE FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ?", [$testUserId]);

// User lưu WELCOME2026
$claim1 = $couponModel->claimVoucherByCode($testUserId, 'WELCOME2026');
assert($claim1['success'] === true, "Lưu WELCOME2026 thành công");
echo "✅ PASS: Lưu mã WELCOME2026 vào ví thành công\n";

// User lưu trùng WELCOME2026 -> Báo đã có trong kho
$claim2 = $couponModel->claimVoucherByCode($testUserId, 'WELCOME2026');
assert($claim2['success'] === false, "Bị chặn lưu trùng");
echo "✅ PASS: Chặn lưu trùng mã voucher trong ví\n";

// User lưu FREESHIP
$claim3 = $couponModel->claimVoucherByCode($testUserId, 'FREESHIP');
assert($claim3['success'] === true, "Lưu FREESHIP thành công");
echo "✅ PASS: Lưu mã FREESHIP vào ví thành công\n";

// 4. Kiểm tra phân loại tabs trong Kho Voucher cá nhân
echo "\n--- 4. Kiểm tra phân loại tabs Kho Voucher (Tất cả, Vận chuyển, Giảm giá) ---\n";
$allCoupons = $couponModel->getUserWalletCoupons($testUserId, 'available');
assert(count($allCoupons) === 2, "Tất cả có 2 voucher");

$shipCoupons = $couponModel->getUserWalletCoupons($testUserId, 'available', 'mien_phi_van_chuyen');
assert(count($shipCoupons) === 1, "Tab Vận chuyển có đúng 1 voucher FREESHIP");
assert($shipCoupons[0]['ma'] === 'FREESHIP');

$discCoupons = $couponModel->getUserWalletCoupons($testUserId, 'available', 'giam_gia');
assert(count($discCoupons) === 1, "Tab Giảm giá có đúng 1 voucher WELCOME2026");
assert($discCoupons[0]['ma'] === 'WELCOME2026');
echo "✅ PASS: Phân loại tabs Kho Voucher chuẩn Shopee (Tất cả: 2, Vận chuyển: 1, Giảm giá: 1)\n";

// 5. Kiểm tra Áp Dụng Đồng Thời Kép (Dual Voucher Stacking) khi Đặt Hàng
echo "\n--- 5. Kiểm tra Đặt hàng Áp Dụng Kép: 1 Mã Giảm Giá + 1 Mã Freeship ---\n";
// Lấy 1 cuốn sách có sẵn tồn kho
$book = $helper->getOne("SELECT * FROM `sach` WHERE `so_luong_ton` >= 2 AND `dang_ban` = 1 LIMIT 1");
assert($book !== null, "Tìm thấy sách test");

$cartItems = [[
    'sach_id'    => (int)$book['id'],
    'so_luong'   => 2,
    'gia'        => 200000.0,
    'thanh_tien' => 400000.0
]];

$subtotal = 400000.0;
$shippingFee = 22000.0;
$discountAmount = 40000.0; // 10% của 400k = 40k
$shippingDiscountAmount = 22000.0; // Freeship toàn bộ
$grandTotal = $subtotal - $discountAmount + max(0.0, $shippingFee - $shippingDiscountAmount); // 360k

$orderData = [
    'nguoi_dung_id'   => $testUserId,
    'nguoi_nhan'      => 'Nguyen Van Test (0901234567)',
    'dia_chi_giao'    => '123 Đường Sách, Q.1, TP.HCM',
    'tong_tien_hang'  => $subtotal,
    'tien_giam'       => $discountAmount,
    'tien_giam_ship'  => $shippingDiscountAmount,
    'phi_van_chuyen'  => $shippingFee,
    'tong_thanh_toan' => $grandTotal,
    'phuong_thuc'     => 'COD',
    'ghi_chu_khach'   => 'Test Shopee Dual Voucher'
];

$orderResult = $orderModel->createOrder($orderData, $cartItems, $welcome, false, $freeship);
assert($orderResult['success'] === true, "Tạo đơn hàng kép thành công: " . ($orderResult['message'] ?? ''));
$testOrderId = (int)$orderResult['order_id'];
echo "✅ PASS: Đặt hàng thành công đơn kép #BK-{$testOrderId}\n";

// Kiểm tra trong CSDL đơn hàng
$savedOrder = $orderModel->getOrderDetail($testOrderId);
assert($savedOrder !== null, "Lấy được chi tiết đơn hàng");
assert((int)$savedOrder['ma_giam_gia_id'] === (int)$welcome['id'], "Đơn lưu đúng ma_giam_gia_id");
assert((int)$savedOrder['ma_freeship_id'] === (int)$freeship['id'], "Đơn lưu đúng ma_freeship_id");
assert((float)$savedOrder['tien_giam'] === 40000.0, "tien_giam đúng 40.000đ");
assert((float)$savedOrder['tien_giam_ship'] === 22000.0, "tien_giam_ship đúng 22.000đ");
assert((float)$savedOrder['tong_thanh_toan'] === 360000.0, "tong_thanh_toan đúng 360.000đ");
echo "✅ PASS: CSDL lưu chính xác cả 2 mã ưu đãi: Giảm tiền hàng {$savedOrder['tien_giam']}đ + Giảm cước ship {$savedOrder['tien_giam_ship']}đ\n";

// Kiểm tra ví sau khi đặt: Cả 2 voucher phải chuyển sang da_dung
$remainAvailable = $couponModel->getUserWalletCoupons($testUserId, 'available');
assert(count($remainAvailable) === 0, "Kho khả dụng còn 0 voucher sau khi dùng");
echo "✅ PASS: Cả 2 voucher đã dùng biến mất hoàn toàn khỏi Kho khả dụng\n";

// Kiểm tra tab "Voucher Đã Sử Dụng": Có đủ 2 voucher và liên kết đúng đơn #BK-testOrderId
$usedList = $couponModel->getUsedCouponsWithOrders($testUserId);
assert(count($usedList) === 2, "Tab đã sử dụng có đủ 2 voucher");
foreach ($usedList as $u) {
    assert((int)$u['don_hang_id'] === $testOrderId, "Voucher đã dùng liên kết đúng mã đơn hàng");
}
echo "✅ PASS: Tab 'Voucher Đã Sử Dụng' hiển thị đủ 2 mã và liên kết chính xác đơn #BK-{$testOrderId}\n";

// 6. Kiểm tra Hoàn Voucher Tự Động khi HỦY ĐƠN HÀNG (Shopee Refund Voucher)
echo "\n--- 6. Kiểm tra Hoàn trả đồng thời cả 2 voucher khi HỦY ĐƠN HÀNG ---\n";
$cancelResult = $orderModel->cancelOrder($testOrderId, $testUserId, 'Đổi ý không mua nữa');
assert($cancelResult['success'] === true, "Hủy đơn hàng thành công");
echo "✅ PASS: Khách hủy đơn hàng #BK-{$testOrderId} thành công\n";

// Sau khi hủy, cả 2 voucher phải được hoàn trả lại ví khả dụng
$restoredAvailable = $couponModel->getUserWalletCoupons($testUserId, 'available');
assert(count($restoredAvailable) === 2, "Cả 2 voucher đã được hoàn trả lại ví khả dụng");
echo "✅ PASS: Cả 2 voucher (Giảm giá + Freeship) đã được HOÀN TRẢ NGAY VÀO VÍ để khách dùng lại!\n";

// Dọn dẹp dữ liệu test
$helper->runSql("DELETE FROM `chi_tiet_don_hang` WHERE `don_hang_id` = ?", [$testOrderId]);
$helper->runSql("DELETE FROM `lich_su_don_hang` WHERE `don_hang_id` = ?", [$testOrderId]);
$helper->runSql("DELETE FROM `thanh_toan` WHERE `don_hang_id` = ?", [$testOrderId]);
$helper->runSql("DELETE FROM `don_hang` WHERE `id` = ?", [$testOrderId]);
$helper->runSql("DELETE FROM `voucher_nguoi_dung` WHERE `nguoi_dung_id` = ?", [$testUserId]);

echo "\n🎉 TẤT CẢ 6 NHÓM KIỂM THỬ VOUCHER CHUẨN THƯƠNG MẠI ĐIỆN TỬ (SHOPEE STYLE) ĐỀU ĐẠT 100%!\n";
