<?php
$testUrls = [
    'hon_truong_ba_1' => 'https://cdn1.fahasa.com/media/catalog/product/i/m/image_232595.jpg',
    'hon_truong_ba_2' => 'https://cdn1.fahasa.com/media/catalog/product/h/a/hang-truong-ba-da-hang-thit-bia.jpg',
    'jbl' => 'https://covers.openlibrary.org/b/id/1819606-L.jpg'
];

foreach ($testUrls as $name => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$name => HTTP $code, " . strlen($data) . " bytes\n";
}
