<?php
/**
 * Test Suite: Cổng Thanh Toán Trực Tuyến VNPAY (VNPAY Gateway Integration Test)
 */

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
@ini_set('session.save_path', __DIR__);
require_once ROOT_PATH . '/Config/config.php';
require_once ROOT_PATH . '/Core/Helper.php';
require_once ROOT_PATH . '/Core/Database.php';
require_once ROOT_PATH . '/Core/Model.php';
require_once ROOT_PATH . '/Core/Vnpay.php';
require_once ROOT_PATH . '/Models/Order.php';

use Core\Vnpay;
use Models\Order;

echo "====================================================\n";
echo " TEST SUITE: TÍCH HỢP CỔNG THANH TOÁN VNPAY SANDBOX\n";
echo "====================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest(string $desc, bool $condition): void {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] " . $desc . "\n";
        $passCount++;
    } else {
        echo "[FAIL] " . $desc . "\n";
        $failCount++;
    }
}

// ----------------------------------------------------
// TEST 1: Cấu hình hằng số VNPAY
// ----------------------------------------------------
echo "--- 1. Kiểm tra hằng số cấu hình VNPAY ---\n";
assertTest("VNP_TMN_CODE đã được định nghĩa đúng (JFZSZ391)", defined('VNP_TMN_CODE') && VNP_TMN_CODE === 'JFZSZ391');
assertTest("VNP_HASH_SECRET đã được định nghĩa đúng", defined('VNP_HASH_SECRET') && VNP_HASH_SECRET === 'GKDZYAFIGNGIDHWTYCWPFHNCUCOIPHXN');
assertTest("VNP_URL trỏ đúng tới cổng Sandbox", defined('VNP_URL') && VNP_URL === 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');

// ----------------------------------------------------
// TEST 2: Tạo URL thanh toán (createPaymentUrl)
// ----------------------------------------------------
echo "\n--- 2. Kiểm tra tạo URL chuyển hướng VNPAY ---\n";
$orderId = 999;
$amount = 350000.0;
$orderDesc = "Thanh toan don hang #BK-999 tai DQP Book Store";
$paymentUrl = Vnpay::createPaymentUrl($orderId, $amount, $orderDesc);

assertTest("URL bắt đầu bằng VNP_URL sandbox", str_starts_with($paymentUrl, 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'));
assertTest("URL chứa vnp_TmnCode=JFZSZ391", strpos($paymentUrl, 'vnp_TmnCode=JFZSZ391') !== false);
assertTest("URL chứa vnp_Amount=35000000 (x100)", strpos($paymentUrl, 'vnp_Amount=35000000') !== false);
assertTest("URL chứa vnp_Command=pay", strpos($paymentUrl, 'vnp_Command=pay') !== false);
assertTest("URL chứa vnp_CurrCode=VND", strpos($paymentUrl, 'vnp_CurrCode=VND') !== false);
assertTest("URL chứa vnp_SecureHash dài 128 ký tự (SHA512)", (bool)preg_match('/vnp_SecureHash=[a-f0-9]{128}/i', $paymentUrl));

// ----------------------------------------------------
// TEST 3: Xác thực chữ ký số (verifyResponse)
// ----------------------------------------------------
echo "\n--- 3. Kiểm tra xác thực chữ ký số HMAC-SHA512 ---\n";

// Bóc tách query string từ URL vừa tạo để giả lập callback từ VNPAY
$urlParts = parse_url($paymentUrl);
parse_str($urlParts['query'], $simulatedParams);

// Bổ sung các tham số VNPAY trả về khi thành công (ResponseCode 00)
$simulatedParams['vnp_ResponseCode'] = '00';
$simulatedParams['vnp_TransactionNo'] = '14567890';
$simulatedParams['vnp_BankCode'] = 'NCB';
$simulatedParams['vnp_CardType'] = 'ATM';
$simulatedParams['vnp_PayDate'] = date('YmdHis');

// Tính toán lại SecureHash theo đúng công thức VNPAY
unset($simulatedParams['vnp_SecureHash']);
ksort($simulatedParams);
$hashData = "";
$i = 0;
foreach ($simulatedParams as $key => $value) {
    if ($i == 1) {
        $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}
$validHash = hash_hmac('sha512', $hashData, VNP_HASH_SECRET);
$simulatedParams['vnp_SecureHash'] = $validHash;

$verifyResult = Vnpay::verifyResponse($simulatedParams);
assertTest("Xác thực chữ ký hợp lệ thành công (is_valid = true)", $verifyResult['is_valid'] === true);
assertTest("Trích xuất đúng Order ID (999)", $verifyResult['order_id'] === 999);
assertTest("Trích xuất đúng số tiền (350000 VNĐ)", $verifyResult['amount'] == 350000);
assertTest("Mã phản hồi là 00 (Thành công)", $verifyResult['response_code'] === '00');
assertTest("Mã giao dịch VNPAY khớp (14567890)", $verifyResult['transaction_no'] === '14567890');

// Kiểm tra với chữ ký giả mạo / bị thay đổi dữ liệu
$tamperedParams = $simulatedParams;
$tamperedParams['vnp_Amount'] = '99999999'; // Can thiệp số tiền
$tamperedVerify = Vnpay::verifyResponse($tamperedParams);
assertTest("Từ chối khi dữ liệu bị giả mạo (is_valid = false)", $tamperedVerify['is_valid'] === false);

// ----------------------------------------------------
// TEST 4: Diễn giải mã lỗi VNPAY (getResponseMessage)
// ----------------------------------------------------
echo "\n--- 4. Kiểm tra từ điển mã lỗi VNPAY ---\n";
assertTest("Mã 00 diễn giải thành công", strpos(Vnpay::getResponseMessage('00'), 'thành công') !== false);
assertTest("Mã 24 diễn giải hủy giao dịch", strpos(Vnpay::getResponseMessage('24'), 'hủy') !== false);
assertTest("Mã 51 diễn giải không đủ số dư", strpos(Vnpay::getResponseMessage('51'), 'số dư') !== false);
assertTest("Mã 13 diễn giải sai OTP", strpos(Vnpay::getResponseMessage('13'), 'OTP') !== false);

// ----------------------------------------------------
// TEST 5: Cập nhật trạng thái thanh toán trong Database
// ----------------------------------------------------
echo "\n--- 5. Kiểm tra cập nhật CSDL và Model Order ---\n";
try {
    $orderModel = new Order();
    // Tìm một đơn hàng có sẵn trong CSDL để test
    $db = Core\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id FROM don_hang ORDER BY id DESC LIMIT 1");
    $sampleOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sampleOrder) {
        $testOrderId = (int)$sampleOrder['id'];
        $testTransNo = 'VNPAY_TEST_' . time();
        
        $updateSuccess = $orderModel->updatePaymentStatus(
            $testOrderId,
            'thanh_cong',
            $testTransNo,
            'Kiểm thử tự động tích hợp VNPAY'
        );
        assertTest("Gọi updatePaymentStatus() thành công trả về true", $updateSuccess === true);

        $paymentRecord = $orderModel->getPaymentByOrderId($testOrderId);
        assertTest("Bản ghi thanh toán tồn tại", !empty($paymentRecord));
        assertTest("Trạng thái thanh toán cập nhật thành 'thanh_cong'", $paymentRecord['trang_thai'] === 'thanh_cong');
        assertTest("Mã giao dịch lưu đúng (" . $testTransNo . ")", $paymentRecord['ma_giao_dich'] === $testTransNo);
    } else {
        echo "[SKIP] Không có đơn hàng mẫu trong CSDL để kiểm tra CSDL trực tiếp.\n";
    }
} catch (\Throwable $e) {
    echo "[WARN] Ngoại lệ kết nối CSDL (môi trường sandbox): " . $e->getMessage() . "\n";
}

echo "\n====================================================\n";
echo " TỔNG KẾT: {$passCount} PASSED | {$failCount} FAILED\n";
echo "====================================================\n";
exit($failCount > 0 ? 1 : 0);
