<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$uploadDir = __DIR__ . '/../Public/uploads/books/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$updates = [
    10 => [
        'title' => 'JBL',
        'url' => 'https://covers.openlibrary.org/b/id/1819606-L.jpg',
        'filename' => 'book_10_jbl.jpg',
        'alias_filename' => 'book_6afbd16e04f782.23517325.png'
    ],
    11 => [
        'title' => 'Truyện Kiều',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/t/r/truyen-kieu_sach-tranh_bia_1.jpg',
        'filename' => 'book_11_truyen_kieu.jpg'
    ],
    12 => [
        'title' => 'Số Đỏ',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0e/Sodobanindau.jpg',
        'filename' => 'book_12_so_do.jpg'
    ],
    13 => [
        'title' => 'Chí Phèo',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/4/42/Chi_Pheo_cover_1957.jpg',
        'filename' => 'book_13_chi_pheo.jpg'
    ],
    14 => [
        'title' => 'Dế Mèn Phiêu Lưu Ký',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/9/91/B%C3%ACa_D%E1%BA%BF_M%C3%A8n_Phi%C3%AAu_L%C6%B0u_K%C3%BD_c%E1%BB%A7a_NXB_T%C3%A2n_D%C3%A2n.jpg',
        'filename' => 'book_14_de_men.jpg'
    ],
    15 => [
        'title' => 'Nỗi Buồn Chiến Tranh',
        'url' => 'https://upload.wikimedia.org/wikipedia/vi/1/11/Noi_buon_chien_tranh.jpg',
        'filename' => 'book_15_noi_buon_chien_tranh.jpg'
    ],
    16 => [
        'title' => 'Tắt Đèn',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/t/a/tat_den_1_2018_11_07_16_44_08.JPG',
        'filename' => 'book_16_tat_den.jpg'
    ],
    17 => [
        'title' => 'Lão Hạc',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/l/a/lao_hac_1_2018_07_25_13_59_49.JPG',
        'filename' => 'book_17_lao_hac.jpg'
    ],
    18 => [
        'title' => 'Hai Đứa Trẻ',
        'url' => 'https://cdn0.fahasa.com/media/catalog/product/9/7/9786043943078_1.jpg',
        'filename' => 'book_18_hai_dua_tre.jpg'
    ],
    19 => [
        'title' => 'Hồn Trương Ba, Da Hàng Thịt',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/i/m/image_232595.jpg',
        'filename' => 'book_19_hon_truong_ba.jpg'
    ]
];

function downloadFile($url, $dest) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200 && strlen($data) > 2000) {
        file_put_contents($dest, $data);
        return true;
    }
    return false;
}

$db = Core\Database::getInstance()->getConnection();
$successCount = 0;

echo "Starting download and update for " . count($updates) . " books...\n";

foreach ($updates as $id => $info) {
    $destPath = $uploadDir . $info['filename'];
    echo "Downloading [{$id}] {$info['title']}...\n";
    $ok = downloadFile($info['url'], $destPath);
    if (!$ok) {
        echo "❌ FAILED to download {$info['url']}\n";
        continue;
    }

    $size = filesize($destPath);
    $imgDetails = @getimagesize($destPath);
    $dim = $imgDetails ? ($imgDetails[0] . 'x' . $imgDetails[1] . ' ' . $imgDetails['mime']) : 'invalid';
    echo "  Saved: {$info['filename']} ({$size} bytes, {$dim})\n";

    if (!empty($info['alias_filename'])) {
        copy($destPath, $uploadDir . $info['alias_filename']);
        echo "  Also copied to alias: {$info['alias_filename']}\n";
    }

    // Update database
    $stmt = $db->prepare("UPDATE sach SET anh_bia = ? WHERE id = ?");
    $stmt->execute([$info['filename'], $id]);
    echo "  Updated database: sach ID {$id} => {$info['filename']}\n";
    $successCount++;
}

echo "\nCompleted: {$successCount}/" . count($updates) . " books successfully updated.\n";
