<?php
$titles = [
    'Tập tin:Noi buon chien tranh.jpg',
    'Tập tin:Tắt đèn-Nhã Nam.jpeg',
    'Tập tin:ThuykieuTruyen.jpg'
];

$url = 'https://vi.wikipedia.org/w/api.php?action=query&titles=' . urlencode(implode('|', $titles)) . '&prop=imageinfo&iiprop=url&format=json';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'BookstoreAuditBot/1.0');
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);
$pages = $data['query']['pages'] ?? [];
foreach ($pages as $p) {
    echo ($p['title'] ?? 'unknown') . " => " . ($p['imageinfo'][0]['url'] ?? 'NO_URL') . "\n";
}
