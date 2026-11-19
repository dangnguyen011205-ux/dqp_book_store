<?php
$candidates = [
    10 => [
        'name' => 'JBL',
        'url' => 'https://covers.openlibrary.org/b/id/1819606-L.jpg',
        'file' => 'book_10_jbl_story.jpg'
    ],
    11 => [
        'name' => 'Truyện Kiều',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/4/45/ThuykieuTruyen.jpg',
        'alt_url' => 'https://covers.openlibrary.org/b/id/4506915-L.jpg',
        'file' => 'book_11_truyen_kieu.jpg'
    ],
    12 => [
        'name' => 'Số Đỏ',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0e/Sodobanindau.jpg',
        'file' => 'book_12_so_do.jpg'
    ],
    13 => [
        'name' => 'Chí Phèo',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/4/42/Chi_Pheo_cover_1957.jpg',
        'file' => 'book_13_chi_pheo.jpg'
    ],
    14 => [
        'name' => 'Dế Mèn Phiêu Lưu Ký',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/9/91/B%C3%ACa_D%E1%BA%BF_M%C3%A8n_Phi%C3%AAu_L%C6%B0u_K%C3%BD_c%E1%BB%A7a_NXB_T%C3%A2n_D%C3%A2n.jpg',
        'file' => 'book_14_de_men_phieu_luu_ky.jpg'
    ],
    15 => [
        'name' => 'Nỗi Buồn Chiến Tranh',
        'url' => 'https://upload.wikimedia.org/wikipedia/vi/1/11/Noi_buon_chien_tranh.jpg',
        'file' => 'book_15_noi_buon_chien_tranh.jpg'
    ],
    16 => [
        'name' => 'Tắt Đèn',
        'url' => 'https://upload.wikimedia.org/wikipedia/vi/b/b1/T%E1%BA%AFt_%C4%91%C3%A8n-Nh%C3%A3_Nam.jpeg',
        'file' => 'book_16_tat_den.jpg'
    ],
    17 => [
        'name' => 'Lão Hạc',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/l/a/lao_hac_1_2018_07_25_13_59_49.JPG',
        'file' => 'book_17_lao_hac.jpg'
    ],
    18 => [
        'name' => 'Hai Đứa Trẻ',
        'url' => 'https://cdn0.fahasa.com/media/catalog/product/9/7/9786043943078_1.jpg',
        'file' => 'book_18_hai_dua_tre.jpg'
    ],
    19 => [
        'name' => 'Hồn Trương Ba, Da Hàng Thịt',
        'url' => 'https://cdn1.fahasa.com/media/catalog/product/i/m/image_232595.jpg',
        'file' => 'book_19_hon_truong_ba_da_hang_thit.jpg'
    ],
];

function fetchUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $data];
}

foreach ($candidates as $id => $item) {
    list($code, $data) = fetchUrl($item['url']);
    if ($code !== 200 || strlen($data) < 2000) {
        if (!empty($item['alt_url'])) {
            list($code, $data) = fetchUrl($item['alt_url']);
        }
    }
    $len = strlen($data);
    $isImage = false;
    $dim = 'unknown';
    if ($code === 200 && $len > 2000) {
        $imgInfo = @getimagesizefromstring($data);
        if ($imgInfo !== false) {
            $isImage = true;
            $dim = $imgInfo[0] . 'x' . $imgInfo[1] . ' (' . $imgInfo['mime'] . ')';
        }
    }
    echo sprintf("[%d] %-25s => HTTP %d, %7d bytes, Image: %s, Dim: %s\n", 
        $id, $item['name'], $code, $len, ($isImage ? 'YES' : 'NO'), $dim);
}
