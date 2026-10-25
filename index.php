<?php
// index.php - Front Controller

// 1. Nạp cấu hình hệ thống
require_once __DIR__ . '/Config/config.php';

// 2. Nạp helper functions toàn cục
require_once __DIR__ . '/Core/Helper.php';

// 3. Đăng ký PSR-4 Autoloader cho namespace Core, Models, Controllers
spl_autoload_register(function (string $class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $classPath;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
});

// 4. Khởi chạy ứng dụng
$app = new \Core\App();
