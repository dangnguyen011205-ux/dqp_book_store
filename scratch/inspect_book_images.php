<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, tieu_de, anh_bia, gia FROM sach ORDER BY id ASC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total books: " . count($books) . "\n";
$missingCount = 0;
foreach ($books as $b) {
    $img = $b['anh_bia'];
    $status = '';
    if (empty($img)) {
        $status = '❌ EMPTY (NO IMAGE)';
        $missingCount++;
    } elseif (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
        $status = '🌐 URL: ' . $img;
    } else {
        $localPath = __DIR__ . '/../Public/uploads/books/' . $img;
        if (file_exists($localPath)) {
            $status = '✅ Local file exists: ' . $img . ' (' . filesize($localPath) . ' bytes)';
        } else {
            $status = '❌ MISSING LOCAL FILE: ' . $img;
            $missingCount++;
        }
    }
    echo sprintf("[%d] %s => %s\n", $b['id'], $b['tieu_de'], $status);
}

echo "\nSummary: " . $missingCount . " books missing images out of " . count($books) . "\n";
