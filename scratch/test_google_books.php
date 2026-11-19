<?php
$title = urlencode("Truyện Kiều Nguyễn Du");
$url = "https://www.googleapis.com/books/v1/volumes?q={$title}&maxResults=3";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$res = curl_exec($ch);
curl_close($ch);

$data = json_decode($res, true);
if (!empty($data['items'])) {
    foreach ($data['items'] as $item) {
        $volumeInfo = $item['volumeInfo'] ?? [];
        echo "Title: " . ($volumeInfo['title'] ?? '') . "\n";
        $img = $volumeInfo['imageLinks']['thumbnail'] ?? $volumeInfo['imageLinks']['smallThumbnail'] ?? null;
        echo "Image: " . ($img ?: 'NONE') . "\n";
    }
} else {
    echo "No items found\n";
}
