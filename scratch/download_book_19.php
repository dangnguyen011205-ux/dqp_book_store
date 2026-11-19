<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$uploadDir = __DIR__ . '/../Public/uploads/books/';
$url = 'https://cdn1.fahasa.com/media/catalog/product/h/a/hang-truong-ba-da-hang-thit-bia.jpg';
$dest = $uploadDir . 'book_19_hon_truong_ba.jpg';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
$data = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code: $code, Len: " . strlen($data) . "\n";
if ($code === 200 && strlen($data) > 2000) {
    file_put_contents($dest, $data);
    $imgDetails = @getimagesize($dest);
    $dim = $imgDetails ? ($imgDetails[0] . 'x' . $imgDetails[1] . ' ' . $imgDetails['mime']) : 'invalid';
    echo "Saved to $dest ($dim)\n";

    $db = Core\Database::getInstance()->getConnection();
    $stmt = $db->prepare("UPDATE sach SET anh_bia = ? WHERE id = 19");
    $stmt->execute(['book_19_hon_truong_ba.jpg']);
    echo "Updated book 19 in database!\n";
} else {
    echo "Failed to download!\n";
}
