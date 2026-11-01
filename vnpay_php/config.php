<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TmnCode = "9Z4CKGRG"; // Mã định danh merchant kết nối (Terminal Id) từ vnpay_email.txt
$vnp_HashSecret = "AMHVTTKNIQPTBBMXOMSGEXVPRTQIUGJL"; // Secret key từ vnpay_email.txt
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

// Tự động nhận diện domain và return URL về Bookstore (hỗ trợ Ngrok HTTPS, Localhost & tự động theo tên thư mục)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || (($_SERVER['SERVER_PORT'] ?? 80) == 443)
           || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
$protocol = $isHttps ? "https://" : "http://";
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
$scriptDir = str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '')));
$baseFolder = trim($scriptDir, '/');
$baseFolder = ($baseFolder === '.' || empty($baseFolder)) ? '' : '/' . $baseFolder;
$vnp_Returnurl = $protocol . $host . $baseFolder . "/thanh-toan/vnpay-return";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Config input format / Expire (15 phút)
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
