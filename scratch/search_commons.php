<?php
$searches = [
    'Lao Hac',
    'Thach Lam',
    'Luu Quang Vu',
    'Nam Cao'
];

foreach ($searches as $s) {
    $url = 'https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch=' . urlencode($s) . '&gsrlimit=5&prop=imageinfo&iiprop=url|mime&format=json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'BookstoreAuditBot/1.0');
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    echo "=== $s ===\n";
    $pages = $data['query']['pages'] ?? [];
    foreach ($pages as $p) {
        $info = $p['imageinfo'][0] ?? [];
        echo " - " . ($p['title'] ?? '') . " => " . ($info['url'] ?? '') . "\n";
    }
}
