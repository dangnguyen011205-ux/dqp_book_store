<?php
$titles = [
    11 => 'Truyện Kiều',
    12 => 'Số đỏ',
    13 => 'Chí Phèo',
    14 => 'Dế Mèn phiêu lưu ký',
    15 => 'Nỗi buồn chiến tranh',
    16 => 'Tắt đèn',
    17 => 'Lão Hạc',
    18 => 'Hai đứa trẻ',
    19 => 'Hồn Trương Ba, da hàng thịt'
];

foreach ($titles as $id => $t) {
    $url = 'https://vi.wikipedia.org/w/api.php?action=query&titles=' . urlencode($t) . '&prop=pageimages|images&pithumbsize=600&format=json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BookstoreAuditBot/1.0 (contact: admin@localhost)');
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    $pages = $data['query']['pages'] ?? [];
    foreach ($pages as $pid => $page) {
        $thumb = $page['thumbnail']['source'] ?? null;
        $images = $page['images'] ?? [];
        echo "[$id] $t => " . ($thumb ?: 'NO_THUMB') . "\n";
        if (!$thumb && !empty($images)) {
            echo "   Images found in page:\n";
            foreach (array_slice($images, 0, 5) as $img) {
                echo "   - " . $img['title'] . "\n";
            }
        }
    }
}
