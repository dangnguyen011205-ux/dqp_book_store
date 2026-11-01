<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$vnp_TmnCode = "9Z4CKGRG"; // Mã định danh merchant kết nối (Terminal Id) từ vnpay_email.txt
$vnp_HashSecret = "AMHVTTKNIQPTBBMXOMSGEXVPRTQIUGJL"; // Secret key từ vnpay_email.txt
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";

// Tự động nhận diện domain và return URL về Bookstore
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$vnp_Returnurl = $protocol . $host . "/PHP_nang_cao/book_store_online/thanh-toan/vnpay-return";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";

// Config input format / Expire (15 phút)
$startTime = date("YmdHis");
$expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
