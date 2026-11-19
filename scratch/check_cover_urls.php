<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, tieu_de, anh_bia FROM sach WHERE anh_bia LIKE 'http%'");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Testing actual image byte sizes for " . count($books) . " books...\n";
$reallyBroken = [];
$reallyValid = [];

foreach ($books as $b) {
    $url = $b['anh_bia'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $len = strlen($data);
    // OpenLibrary returns 43 bytes (1x1 blank gif) when not found
    if ($httpCode === 200 && $len > 1000) {
        $reallyValid[] = $b;
        echo sprintf("✅ [%d] %s => %d bytes\n", $b['id'], $b['tieu_de'], $len);
    } else {
        $reallyBroken[] = $b;
        echo sprintf("❌ [%d] %s => %d bytes (BROKEN / BLANK)\n", $b['id'], $b['tieu_de'], $len);
    }
}

echo "\nSummary:\n";
echo "Really Valid: " . count($reallyValid) . "\n";
echo "Really Broken (43 bytes blank): " . count($reallyBroken) . "\n";
