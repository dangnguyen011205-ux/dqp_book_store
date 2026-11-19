<?php
$testUrls = [
    'lao_hac' => 'https://cdn1.fahasa.com/media/catalog/product/l/a/lao_hac_1_2018_07_25_13_59_49.JPG',
    'hai_dua_tre' => 'https://cdn1.fahasa.com/media/catalog/product/9/7/9786044916941.jpg',
    'hai_dua_tre2' => 'https://cdn0.fahasa.com/media/catalog/product/9/7/9786043943078_1.jpg'
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
