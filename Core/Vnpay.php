<?php
namespace Core;

/**
 * Lớp điều phối tích hợp Cổng thanh toán VNPAY (VNPAY Gateway Service)
 * Hỗ trợ tạo URL thanh toán, xác thực chữ ký số HMAC-SHA512 và biên dịch mã phản hồi
 */
class Vnpay {
    /**
     * Tạo URL chuyển hướng sang cổng thanh toán VNPAY Sandbox
     *
     * @param int $orderId Mã ID đơn hàng trong hệ thống
     * @param float $amount Số tiền thanh toán (VNĐ)
     * @param string $orderDesc Nội dung giao dịch
     * @param string $bankCode Mã ngân hàng / phương thức thanh toán (tùy chọn)
     * @param string $language Ngôn ngữ ('vn' hoặc 'en')
     * @return string URL chuyển hướng VNPAY hoàn chỉnh kèm chữ ký số
     */
    public static function createPaymentUrl(int $orderId, float $amount, string $orderDesc = '', string $bankCode = '', string $language = 'vn'): string {
        $tmnCode = defined('VNP_TMN_CODE') ? VNP_TMN_CODE : '9Z4CKGRG';
        $hashSecret = defined('VNP_HASH_SECRET') ? VNP_HASH_SECRET : 'AMHVTTKNIQPTBBMXOMSGEXVPRTQIUGJL';
        $vnpUrl = defined('VNP_URL') ? VNP_URL : 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

        // Xác định địa chỉ IP khách hàng
        $ipAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        if ($ipAddr === '::1' || empty($ipAddr)) {
            $ipAddr = '127.0.0.1';
        }

        // Return URL callback về hệ thống Bookstore
        $returnUrl = function_exists('base_url') ? \base_url('thanh-toan/vnpay-return') : (defined('BASE_URL') ? BASE_URL . '/thanh-toan/vnpay-return' : 'http://localhost/PHP_nang_cao/book_store_online/thanh-toan/vnpay-return');

        // Tạo mã tham chiếu giao dịch (kèm timestamp để cho phép thanh toán lại nếu lần trước thất bại)
        $txnRef = (string)$orderId . '_' . date('His');

        if (empty($orderDesc)) {
            $orderDesc = "Thanh toan don hang BK" . $orderId . " tai DQP Book Store";
        } else {
            // Loại bỏ ký tự đặc biệt bị VNPAY cấm (như #, -, <, >, &, ...)
            $orderDesc = preg_replace('/[^a-zA-Z0-9\s]/', '', str_replace(['#', '-', '_', '.'], ' ', $orderDesc));
            $orderDesc = trim(preg_replace('/\s+/', ' ', (string)$orderDesc));
        }

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $tmnCode,
            "vnp_Amount"     => (int)round($amount * 100), // VNPAY nhân 100 theo đơn vị đồng
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => $startTime,
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $ipAddr,
            "vnp_Locale"     => in_array($language, ['vn', 'en'], true) ? $language : 'vn',
            "vnp_OrderInfo"  => $orderDesc,
            "vnp_OrderType"  => "other",
            "vnp_ReturnUrl"  => $returnUrl,
            "vnp_TxnRef"     => $txnRef,
            "vnp_ExpireDate" => $expire
        ];

        if (!empty($bankCode)) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $fullPaymentUrl = $vnpUrl . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashData, $hashSecret);
        $fullPaymentUrl .= 'vnp_SecureHash=' . $vnpSecureHash;

        return $fullPaymentUrl;
    }

    /**
     * Xác thực chữ ký số HMAC-SHA512 từ phản hồi của VNPAY (Return URL hoặc IPN)
     *
     * @param array $queryParams Dữ liệu nhận từ $_GET hoặc $_POST
     * @return array Kết quả xác thực ['is_valid' => bool, 'order_id' => int, ...]
     */
    public static function verifyResponse(array $queryParams): array {
        $hashSecret = defined('VNP_HASH_SECRET') ? VNP_HASH_SECRET : 'AMHVTTKNIQPTBBMXOMSGEXVPRTQIUGJL';
        $vnpSecureHash = $queryParams['vnp_SecureHash'] ?? '';

        $inputData = [];
        foreach ($queryParams as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $calculatedHash = hash_hmac('sha512', $hashData, $hashSecret);
        $isValid = !empty($vnpSecureHash) && hash_equals(strtolower($calculatedHash), strtolower($vnpSecureHash));

        // Trích xuất order ID từ vnp_TxnRef (hỗ trợ cả dạng "15" hoặc "15_143000")
        $rawTxnRef = $queryParams['vnp_TxnRef'] ?? '';
        $orderId = (int)(explode('_', $rawTxnRef)[0] ?? 0);

        return [
            'is_valid'        => $isValid,
            'order_id'        => $orderId,
            'txn_ref'         => $rawTxnRef,
            'response_code'   => $queryParams['vnp_ResponseCode'] ?? '',
            'transaction_no'  => $queryParams['vnp_TransactionNo'] ?? '',
            'bank_code'       => $queryParams['vnp_BankCode'] ?? '',
            'bank_tran_no'    => $queryParams['vnp_BankTranNo'] ?? '',
            'card_type'       => $queryParams['vnp_CardType'] ?? '',
            'amount'          => isset($queryParams['vnp_Amount']) ? ((float)$queryParams['vnp_Amount'] / 100) : 0.0,
            'pay_date'        => $queryParams['vnp_PayDate'] ?? '',
            'order_info'      => $queryParams['vnp_OrderInfo'] ?? '',
            'raw_data'        => $inputData
        ];
    }

    /**
     * Diễn giải ý nghĩa chi tiết của mã phản hồi vnp_ResponseCode từ VNPAY
     *
     * @param string $code Mã phản hồi từ VNPAY
     * @return string Thông điệp giải thích tiếng Việt
     */
    public static function getResponseMessage(string $code): string {
        return match ($code) {
            '00' => 'Giao dịch thanh toán thành công qua VNPAY.',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới gian lận hoặc bất thường).',
            '09' => 'Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
            '10' => 'Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần.',
            '11' => 'Đã hết hạn chờ thanh toán. Xin vui lòng thực hiện lại giao dịch.',
            '12' => 'Thẻ/Tài khoản của khách hàng đang bị khóa.',
            '13' => 'Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Vui lòng thử lại.',
            '24' => 'Khách hàng đã hủy giao dịch trên cổng thanh toán VNPAY.',
            '51' => 'Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
            '65' => 'Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.',
            '75' => 'Ngân hàng thanh toán hiện đang bảo trì hệ thống. Vui lòng thử lại sau.',
            '79' => 'Khách hàng nhập sai mật khẩu thanh toán quá số lần quy định.',
            default => "Giao dịch không thành công từ VNPAY (Mã lỗi: {$code})."
        };
    }
}
