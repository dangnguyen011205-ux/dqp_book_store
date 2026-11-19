<?php
$searches = [
    17 => 'Lão Hạc Nam Cao',
    18 => 'Hai đứa trẻ Thạch Lam',
    19 => 'Hồn Trương Ba, da hàng thịt',
    11 => 'Truyện Kiều Nguyễn Du',
    10 => 'JBL'
];

foreach ($searches as $id => $q) {
    $url = 'https://openlibrary.org/search.json?q=' . urlencode($q) . '&limit=5';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $res = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    echo "=== [$id] $q ===\n";
    if (!empty($data['docs'])) {
        foreach ($data['docs'] as $doc) {
            $title = $doc['title'] ?? 'No title';
            $cover_i = $doc['cover_i'] ?? null;
            echo " - $title (cover_i: " . ($cover_i ? "https://covers.openlibrary.org/b/id/$cover_i-L.jpg" : 'none') . ")\n";
        }
    } else {
        echo " - No results\n";
    }
}
