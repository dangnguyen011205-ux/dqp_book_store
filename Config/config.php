<?php
// Config/config.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Đường dẫn thư mục gốc vật lý
define('ROOT_PATH', dirname(__DIR__));

// Tên ứng dụng
define('APP_NAME', 'DQP Book Store');
define('APP_SLOGAN', 'Nhà sách Trực tuyến Tinh hoa');
define('HOTLINE', '1900 6868');

// Cấu hình CSDL MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'book_store');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Cấu hình Cổng thanh toán VNPAY (Sandbox Test)
define('VNP_TMN_CODE', 'JFZSZ391');
define('VNP_HASH_SECRET', 'GKDZYAFIGNGIDHWTYCWPFHNCUCOIPHXN');
define('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
define('VNP_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction');

// Tự động nhận diện BASE_URL linh hoạt (hỗ trợ cả Localhost, Ngrok HTTPS, Reverse Proxy)
if (!defined('BASE_URL')) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
               || (($_SERVER['SERVER_PORT'] ?? 80) == 443)
               || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
               || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on');
    $protocol = $isHttps ? "https://" : "http://";
    $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    
    // Nhận diện thư mục chạy từ SCRIPT_NAME (ví dụ: /PHP_nang_cao/book_store_online/index.php)
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseFolder = trim($scriptDir, '/');
    if ($baseFolder === '.' || empty($baseFolder)) {
        $baseFolder = '';
    } else {
        $baseFolder = '/' . $baseFolder;
    }
    
    define('BASE_URL', $protocol . $host . $baseFolder);
}
