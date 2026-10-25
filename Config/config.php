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

// Tự động nhận diện BASE_URL linh hoạt
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Nhận diện thư mục chạy từ SCRIPT_NAME (ví dụ: /PHP_nang_cao/book_store_online/index.php)
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseFolder = rtrim($scriptDir, '/');
    
    define('BASE_URL', $protocol . $host . $baseFolder);
}
