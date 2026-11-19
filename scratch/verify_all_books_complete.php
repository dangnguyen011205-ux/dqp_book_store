<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, tieu_de, anh_bia, gia FROM sach ORDER BY id ASC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=======================================================\n";
echo "AUDIT ALL " . count($books) . " BOOKS IN DATABASE\n";
echo "=======================================================\n";

$validCount = 0;
$brokenCount = 0;
$brokenList = [];

foreach ($books as $b) {
    $img = $b['anh_bia'];
    $isValid = false;
    $details = '';

    if (empty($img)) {
        $details = '❌ EMPTY IMAGE FIELD';
    } elseif (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
        // External URL: test GET request
        $ch = curl_init($img);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $bytes = strlen($data);
        if ($code === 200 && $bytes > 1000) {
            $isValid = true;
            $details = "✅ Online URL ({$bytes} bytes)";
        } else {
            $details = "❌ BROKEN URL: HTTP {$code}, {$bytes} bytes";
        }
    } else {
        // Local file
        $localPath = __DIR__ . '/../Public/uploads/books/' . $img;
        if (file_exists($localPath)) {
            $bytes = filesize($localPath);
            $imgInfo = @getimagesize($localPath);
            if ($bytes > 1000 && $imgInfo !== false) {
                $isValid = true;
                $details = "✅ Local file ({$bytes} bytes, {$imgInfo[0]}x{$imgInfo[1]} {$imgInfo['mime']})";
            } else {
                $details = "❌ INVALID LOCAL IMAGE ({$bytes} bytes)";
            }
        } else {
            $details = "❌ MISSING LOCAL FILE: {$img}";
        }
    }

    if ($isValid) {
        $validCount++;
        echo sprintf("PASS [%2d] %-35s => %s\n", $b['id'], mb_substr($b['tieu_de'], 0, 35), $details);
    } else {
        $brokenCount++;
        $brokenList[] = $b;
        echo sprintf("FAIL [%2d] %-35s => %s\n", $b['id'], mb_substr($b['tieu_de'], 0, 35), $details);
    }
}

echo "=======================================================\n";
echo "AUDIT RESULT: {$validCount}/" . count($books) . " VALID, {$brokenCount} BROKEN\n";
echo "=======================================================\n";
