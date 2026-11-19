<?php
$articles = [
    'Nam Cao',
    'Thạch Lam',
    'Lưu Quang Vũ'
];

foreach ($articles as $art) {
    $url = 'https://vi.wikipedia.org/w/api.php?action=query&titles=' . urlencode($art) . '&prop=images|pageimages&pithumbsize=600&format=json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BookstoreAuditBot/1.0');
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    echo "=== $art ===\n";
    $pages = $data['query']['pages'] ?? [];
    foreach ($pages as $p) {
        echo "Thumb: " . ($p['thumbnail']['source'] ?? 'none') . "\n";
        if (!empty($p['images'])) {
            foreach ($p['images'] as $img) {
                echo "  - " . $img['title'] . "\n";
            }
        }
    }
}
