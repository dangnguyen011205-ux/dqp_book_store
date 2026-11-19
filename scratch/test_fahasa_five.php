<?php
$list = [
    'https://cdn1.fahasa.com/media/catalog/product/8/9/8936067604825.jpg',
    'https://cdn1.fahasa.com/media/catalog/product/9/7/9786044916972.jpg',
    'https://cdn1.fahasa.com/media/catalog/product/8/9/8935236430128.jpg',
    'https://cdn1.fahasa.com/media/catalog/product/9/7/9786043947526.jpg',
    'https://cdn1.fahasa.com/media/catalog/product/i/m/image_235839.jpg'
];

foreach ($list as $u) {
    $ch = curl_init($u);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $info = @getimagesizefromstring($data);
    $dim = $info ? ($info[0] . 'x' . $info[1]) : 'invalid';
    echo "$u => HTTP $code, " . strlen($data) . " bytes, Dim: $dim\n";
}
