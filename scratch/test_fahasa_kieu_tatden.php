<?php
$urls = [
    'tat_den' => 'https://cdn1.fahasa.com/media/catalog/product/t/a/tat_den_1_2018_11_07_16_44_08.JPG',
    'truyen_kieu' => 'https://cdn1.fahasa.com/media/catalog/product/t/r/truyen-kieu_sach-tranh_bia_1.jpg'
];

foreach ($urls as $k => $u) {
    $ch = curl_init($u);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $info = @getimagesizefromstring($data);
    $dim = $info ? ($info[0] . 'x' . $info[1]) : 'invalid';
    echo "$k => HTTP $code, " . strlen($data) . " bytes, Dim: $dim\n";
}
