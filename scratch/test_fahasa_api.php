<?php
$terms = [
    17 => 'Lão Hạc Nam Cao',
    18 => 'Hai Đứa Trẻ Thạch Lam',
    19 => 'Hồn Trương Ba da hàng thịt'
];

foreach ($terms as $id => $q) {
    $url = 'https://www.fahasa.com/fahasa_rest/v2/search/products?q=' . urlencode($q) . '&limit=3';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "[$id] $q => HTTP $code\n";
    $json = json_decode($res, true);
    if (!empty($json['data_products']['products'])) {
        foreach ($json['data_products']['products'] as $p) {
            echo "  - " . ($p['product_name'] ?? '') . " => " . ($p['image_src'] ?? '') . "\n";
        }
    } else {
        echo "  " . substr($res, 0, 150) . "\n";
    }
}
