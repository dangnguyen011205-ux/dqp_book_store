<?php
/**
 * Migration: Flash Sale Table + 50 New Books
 * Run: C:\xampp\php\php.exe scratch/migrate_flash_sale.php
 */

$pdo = new PDO('mysql:host=localhost;dbname=book_store;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES utf8mb4");

echo "=== MIGRATION: Flash Sale System + 50 Books ===" . PHP_EOL . PHP_EOL;

// ============================================================
// 1. TẠO BẢNG flash_sale
// ============================================================
echo "[1] Tạo bảng flash_sale..." . PHP_EOL;
$pdo->exec("
CREATE TABLE IF NOT EXISTS `flash_sale` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sach_id` INT NOT NULL,
  `gia_sale` DECIMAL(12,2) NOT NULL,
  `bat_dau` DATETIME NOT NULL,
  `ket_thuc` DATETIME NOT NULL,
  `created_by` INT DEFAULT NULL,
  `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_flash_sale_sach` FOREIGN KEY (`sach_id`) REFERENCES `sach`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX `idx_flash_bat_dau` (`bat_dau`),
  INDEX `idx_flash_ket_thuc` (`ket_thuc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");
echo "   OK: Bảng flash_sale đã được tạo (hoặc đã tồn tại)." . PHP_EOL . PHP_EOL;

// ============================================================
// 2. THÊM NXB MỚI
// ============================================================
echo "[2] Thêm NXB mới..." . PHP_EOL;
$nxbList = [
    'NXB Hội Nhà Văn',
    'NXB Phụ Nữ',
    'NXB Lao Động - Xã hội',
    'NXB Giáo Dục',
    'NXB Chính Trị Quốc Gia',
    'NXB Văn Học',
    'NXB Dân Trí',
    'NXB Thế Giới',
    'NXB Alpha Books',
    'NXB First News - Trí Việt',
];

$stmtNxb = $pdo->prepare("INSERT INTO nha_xuat_ban (ten) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM nha_xuat_ban WHERE ten = ?)");
foreach ($nxbList as $nxb) {
    $stmtNxb->execute([$nxb, $nxb]);
    if ($stmtNxb->rowCount() > 0) {
        echo "   ADDED NXB: $nxb" . PHP_EOL;
    } else {
        echo "   SKIP NXB (already exists): $nxb" . PHP_EOL;
    }
}

// Build NXB lookup map
$nxbMap = [];
$rows = $pdo->query("SELECT id, ten FROM nha_xuat_ban")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) { $nxbMap[$r['ten']] = (int)$r['id']; }
echo PHP_EOL;

// ============================================================
// 3. THÊM TÁC GIẢ MỚI
// ============================================================
echo "[3] Thêm tác giả mới..." . PHP_EOL;
$authorList = [
    'Ngô Bảo Châu', 'Tô Hoài', 'Nam Cao', 'Nguyễn Du', 'Nguyễn Tuân',
    'Vũ Trọng Phụng', 'Xuân Diệu', 'Hồ Xuân Hương', 'Khái Hưng', 'Thạch Lam',
    'Stephen King', 'George Orwell', 'J.K. Rowling', 'Antoine de Saint-Exupéry', 'Victor Hugo',
    'Leo Tolstoy', 'Fyodor Dostoevsky', 'Mark Twain', 'Charles Dickens', 'Agatha Christie',
    'Tony Robbins', 'Malcolm Gladwell', 'Daniel Kahneman', 'Simon Sinek', 'James Clear',
    'Adam Grant', 'Brené Brown', 'Seth Godin', 'Cal Newport', 'Ryan Holiday',
    'Robert Kiyosaki', 'Napoleon Hill', 'Wallace D. Wattles', 'Og Mandino', 'Brian Tracy',
    // Extra authors needed for specific books
    'Bảo Ninh', 'Ngô Tất Tố', 'Lưu Quang Vũ', 'J.D. Salinger', 'F. Scott Fitzgerald',
    'Lois Lowry', 'Orhan Pamuk', 'Phil Barden', 'George S. Clason', 'T. Harv Eker',
    'David J. Schwartz', 'Robin Sharma', 'Charles Duhigg', 'Stephen R. Covey', 'Keith Ferrazzi',
    'Ichiro Kishimi', 'Andrew Hunt', 'Gayle McDowell', 'Kyle Simpson', 'Arthur Conan Doyle',
    'Harper Lee', 'Robert Louis Stevenson', 'CLRS',
];

$stmtTg = $pdo->prepare("INSERT INTO tac_gia (ten) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM tac_gia WHERE ten = ?)");
foreach ($authorList as $author) {
    $stmtTg->execute([$author, $author]);
    if ($stmtTg->rowCount() > 0) {
        echo "   ADDED: $author" . PHP_EOL;
    }
}

// Build author lookup map
$authorMap = [];
$rows = $pdo->query("SELECT id, ten FROM tac_gia")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) { $authorMap[$r['ten']] = (int)$r['id']; }
echo PHP_EOL;

// ============================================================
// 4. THÊM 50 SÁCH MỚI
// ============================================================
echo "[4] Thêm 50 sách mới..." . PHP_EOL;

// NXB ID helpers
$nxbHoiNhaVan    = $nxbMap['NXB Hội Nhà Văn']          ?? null;
$nxbPhuNu        = $nxbMap['NXB Phụ Nữ']                ?? null;
$nxbLaoDong      = $nxbMap['NXB Lao Động - Xã hội']     ?? null;
$nxbGiaoDuc      = $nxbMap['NXB Giáo Dục']               ?? null;
$nxbChinhTri     = $nxbMap['NXB Chính Trị Quốc Gia']    ?? null;
$nxbVanHoc       = $nxbMap['NXB Văn Học']                ?? null;
$nxbDanTri       = $nxbMap['NXB Dân Trí']                ?? null;
$nxbTheGioi      = $nxbMap['NXB Thế Giới']               ?? null;
$nxbAlpha        = $nxbMap['NXB Alpha Books']            ?? null;
$nxbFirstNews    = $nxbMap['NXB First News - Trí Việt']  ?? null;
$nxbTre          = $nxbMap['NXB Trẻ']                    ?? 1;
$nxbKimDong      = $nxbMap['NXB Kim Đồng']               ?? 2;
$nxbTongHop      = $nxbMap['NXB Tổng Hợp TP.HCM']       ?? 3;
$nxbThongTin     = $nxbMap['NXB Thông Tin và Truyền Thông'] ?? 4;

$books = [
    // ---- Văn học Việt Nam (the_loai_id=1) ----
    [
        'tieu_de'     => 'Truyện Kiều',
        'tac_gia'     => ['Nguyễn Du'],
        'gia'         => 68000,
        'mo_ta'       => 'Truyện Kiều là kiệt tác của đại thi hào Nguyễn Du, tác phẩm nổi tiếng nhất của văn học cổ điển Việt Nam kể về cuộc đời người con gái tài sắc vẹn toàn Thuý Kiều.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786044837185-L.jpg',
        'so_trang'    => 376,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 100,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbVanHoc,
    ],
    [
        'tieu_de'     => 'Số Đỏ',
        'tac_gia'     => ['Vũ Trọng Phụng'],
        'gia'         => 55000,
        'mo_ta'       => 'Số Đỏ là tiểu thuyết trào phúng nổi tiếng của Vũ Trọng Phụng, phê phán xã hội thành thị Việt Nam thời Pháp thuộc qua nhân vật Xuân Tóc Đỏ.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047336685-L.jpg',
        'so_trang'    => 288,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 80,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Chí Phèo',
        'tac_gia'     => ['Nam Cao'],
        'gia'         => 45000,
        'mo_ta'       => 'Chí Phèo là truyện ngắn xuất sắc của Nam Cao, miêu tả bi kịch của người nông dân bị tha hóa trong xã hội phong kiến nửa thực dân.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047352227-L.jpg',
        'so_trang'    => 192,
        'nam_xuat_ban'=> 2018,
        'so_luong_ton'=> 90,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbVanHoc,
    ],
    [
        'tieu_de'     => 'Dế Mèn Phiêu Lưu Ký',
        'tac_gia'     => ['Tô Hoài'],
        'gia'         => 48000,
        'mo_ta'       => 'Dế Mèn Phiêu Lưu Ký là tác phẩm thiếu nhi kinh điển của nhà văn Tô Hoài, kể về cuộc phiêu lưu của chú dế mèn dũng cảm khắp thế giới côn trùng.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786041131422-L.jpg',
        'so_trang'    => 224,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 120,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbKimDong,
    ],
    [
        'tieu_de'     => 'Nỗi Buồn Chiến Tranh',
        'tac_gia'     => ['Bảo Ninh'],
        'gia'         => 85000,
        'mo_ta'       => 'Nỗi Buồn Chiến Tranh là tiểu thuyết nổi tiếng của Bảo Ninh, được xem là một trong những tác phẩm văn học chiến tranh xuất sắc nhất của Việt Nam.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047346059-L.jpg',
        'so_trang'    => 310,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 60,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Tắt Đèn',
        'tac_gia'     => ['Ngô Tất Tố'],
        'gia'         => 52000,
        'mo_ta'       => 'Tắt Đèn là tiểu thuyết hiện thực phê phán của Ngô Tất Tố, phản ánh cuộc sống cơ cực của người nông dân Việt Nam dưới ách thực dân phong kiến.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047350186-L.jpg',
        'so_trang'    => 198,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 70,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbVanHoc,
    ],
    [
        'tieu_de'     => 'Lão Hạc',
        'tac_gia'     => ['Nam Cao'],
        'gia'         => 42000,
        'mo_ta'       => 'Lão Hạc là truyện ngắn cảm động của Nam Cao về người nông dân nghèo khổ có tấm lòng nhân hậu và tình yêu thương sâu sắc.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047352333-L.jpg',
        'so_trang'    => 168,
        'nam_xuat_ban'=> 2018,
        'so_luong_ton'=> 85,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbVanHoc,
    ],
    [
        'tieu_de'     => 'Hai Đứa Trẻ',
        'tac_gia'     => ['Thạch Lam'],
        'gia'         => 38000,
        'mo_ta'       => 'Hai Đứa Trẻ là tuyển tập truyện ngắn trữ tình của Thạch Lam, với lối viết tinh tế, nhẹ nhàng khắc họa cuộc sống đời thường.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047376506-L.jpg',
        'so_trang'    => 156,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 75,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Hồn Trương Ba, Da Hàng Thịt',
        'tac_gia'     => ['Lưu Quang Vũ'],
        'gia'         => 65000,
        'mo_ta'       => 'Hồn Trương Ba, Da Hàng Thịt là vở kịch nổi tiếng của Lưu Quang Vũ, triết lý sâu sắc về sự sống, cái chết và bản thể con người.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9786047359721-L.jpg',
        'so_trang'    => 212,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 55,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbSanKhau ?? $nxbVanHoc,
    ],
    [
        'tieu_de'     => 'Những Người Khốn Khổ',
        'tac_gia'     => ['Victor Hugo'],
        'gia'         => 185000,
        'mo_ta'       => 'Những Người Khốn Khổ là tiểu thuyết vĩ đại của Victor Hugo, kể về hành trình chuộc tội của Jean Valjean trong xã hội Pháp thế kỷ 19.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780140444308-L.jpg',
        'so_trang'    => 1232,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 50,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTheGioi,
    ],
    // ---- Văn học nước ngoài (the_loai_id=1) ----
    [
        'tieu_de'     => '1984',
        'tac_gia'     => ['George Orwell'],
        'gia'         => 95000,
        'mo_ta'       => '1984 là tiểu thuyết dystopia kinh điển của George Orwell, phác họa xã hội toàn trị nơi Đảng kiểm soát tuyệt đối tư tưởng và cuộc sống con người.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg',
        'so_trang'    => 328,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 100,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Harry Potter và Hòn Đá Phù Thủy',
        'tac_gia'     => ['J.K. Rowling'],
        'gia'         => 120000,
        'mo_ta'       => 'Harry Potter và Hòn Đá Phù Thủy là tập đầu tiên của bộ truyện huyền thoại về cậu bé phù thủy Harry Potter và thế giới phép thuật kỳ diệu.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780439708180-L.jpg',
        'so_trang'    => 352,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 150,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTre,
    ],
    [
        'tieu_de'     => 'Hoàng Tử Bé',
        'tac_gia'     => ['Antoine de Saint-Exupéry'],
        'gia'         => 75000,
        'mo_ta'       => 'Hoàng Tử Bé là tuyệt tác văn học của Antoine de Saint-Exupéry, câu chuyện triết lý nhân văn về tình bạn, tình yêu và những giá trị vô hình.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780156012195-L.jpg',
        'so_trang'    => 96,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 200,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTheGioi,
    ],
    [
        'tieu_de'     => 'Tội Ác và Hình Phạt',
        'tac_gia'     => ['Fyodor Dostoevsky'],
        'gia'         => 145000,
        'mo_ta'       => 'Tội Ác và Hình Phạt là tiểu thuyết tâm lý vĩ đại của Dostoevsky, khám phá tâm lý tội lỗi và sự chuộc tội của sinh viên Raskolnikov.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780140449136-L.jpg',
        'so_trang'    => 671,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 60,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTheGioi,
    ],
    [
        'tieu_de'     => 'Chiến Tranh và Hòa Bình',
        'tac_gia'     => ['Leo Tolstoy'],
        'gia'         => 220000,
        'mo_ta'       => 'Chiến Tranh và Hòa Bình là sử thi vĩ đại của Leo Tolstoy, tái hiện bức tranh toàn cảnh nước Nga trong cuộc chiến chống Napoleon.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780140444174-L.jpg',
        'so_trang'    => 1392,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 40,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTheGioi,
    ],
    [
        'tieu_de'     => 'Đảo Giấu Vàng',
        'tac_gia'     => ['Robert Louis Stevenson'],
        'gia'         => 68000,
        'mo_ta'       => 'Đảo Giấu Vàng là tiểu thuyết phiêu lưu kinh điển của Robert Louis Stevenson, kể về cuộc hành trình tìm kho báu trên hòn đảo bí ẩn.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780141321035-L.jpg',
        'so_trang'    => 272,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 80,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbKimDong,
    ],
    [
        'tieu_de'     => 'Bắt Trẻ Đồng Xanh',
        'tac_gia'     => ['J.D. Salinger'],
        'gia'         => 88000,
        'mo_ta'       => 'Bắt Trẻ Đồng Xanh (The Catcher in the Rye) là tiểu thuyết kinh điển của J.D. Salinger, câu chuyện về sự nổi loạn của tuổi trẻ qua nhân vật Holden Caulfield.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780316769174-L.jpg',
        'so_trang'    => 288,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 70,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Gatsby Vĩ Đại',
        'tac_gia'     => ['F. Scott Fitzgerald'],
        'gia'         => 79000,
        'mo_ta'       => 'Gatsby Vĩ Đại là tiểu thuyết trác tuyệt của F. Scott Fitzgerald về giấc mơ Mỹ, tình yêu và bi kịch của tầng lớp thượng lưu những năm 1920.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
        'so_trang'    => 192,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 85,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
    [
        'tieu_de'     => 'Người Giữ Những Ký Ức',
        'tac_gia'     => ['Lois Lowry'],
        'gia'         => 85000,
        'mo_ta'       => 'Người Giữ Những Ký Ức (The Giver) là tiểu thuyết dystopia nổi tiếng của Lois Lowry, kể về một xã hội tưởng như hoàn hảo nhưng thiếu vắng cảm xúc và ký ức.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780440237457-L.jpg',
        'so_trang'    => 240,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 65,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTre,
    ],
    [
        'tieu_de'     => 'Tên Tôi Là Đỏ',
        'tac_gia'     => ['Orhan Pamuk'],
        'gia'         => 115000,
        'mo_ta'       => 'Tên Tôi Là Đỏ là tiểu thuyết đoạt giải Nobel của Orhan Pamuk, khám phá bí ẩn và nghệ thuật trong nền văn hóa Ottoman thế kỷ 16.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781400078998-L.jpg',
        'so_trang'    => 432,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 45,
        'the_loai_id' => 1,
        'nxb_id'      => $nxbTheGioi,
    ],
    // ---- Kinh tế & Quản trị (the_loai_id=2) ----
    [
        'tieu_de'     => 'Tư Duy Nhanh Và Chậm',
        'tac_gia'     => ['Daniel Kahneman'],
        'gia'         => 145000,
        'mo_ta'       => 'Tư Duy Nhanh Và Chậm (Thinking, Fast and Slow) là kiệt tác của nhà tâm lý học đoạt Nobel Daniel Kahneman về hai hệ thống tư duy của con người.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg',
        'so_trang'    => 499,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 90,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Hiệu Ứng Chim Mồi',
        'tac_gia'     => ['Phil Barden'],
        'gia'         => 128000,
        'mo_ta'       => 'Hiệu Ứng Chim Mồi (Decoded) là cuốn sách tiếp thị ứng dụng khoa học thần kinh để giải mã quyết định mua hàng của người tiêu dùng.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780470974025-L.jpg',
        'so_trang'    => 288,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 60,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Bắt Đầu Với Câu Hỏi Tại Sao',
        'tac_gia'     => ['Simon Sinek'],
        'gia'         => 112000,
        'mo_ta'       => 'Bắt Đầu Với Câu Hỏi Tại Sao (Start With Why) của Simon Sinek giải thích tại sao một số cá nhân và tổ chức truyền cảm hứng trong khi những người khác thì không.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781591846444-L.jpg',
        'so_trang'    => 256,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 100,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Người Giàu Nhất Thành Babylon',
        'tac_gia'     => ['George S. Clason'],
        'gia'         => 68000,
        'mo_ta'       => 'Người Giàu Nhất Thành Babylon là cuốn sách tài chính cá nhân kinh điển, truyền đạt các nguyên tắc quản lý tiền bạc qua những câu chuyện về thành Babylon cổ đại.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781505339116-L.jpg',
        'so_trang'    => 192,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 120,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Tư Duy Triệu Phú',
        'tac_gia'     => ['T. Harv Eker'],
        'gia'         => 95000,
        'mo_ta'       => 'Tư Duy Triệu Phú (Secrets of the Millionaire Mind) khám phá sự khác biệt trong tư duy tài chính giữa người giàu và người nghèo.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780061254895-L.jpg',
        'so_trang'    => 224,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 85,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Dám Nghĩ Lớn',
        'tac_gia'     => ['David J. Schwartz'],
        'gia'         => 88000,
        'mo_ta'       => 'Dám Nghĩ Lớn (The Magic of Thinking Big) của David J. Schwartz là cuốn sách truyền động lực giúp bạn tư duy lớn hơn để đạt được thành công vượt trội.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780671741457-L.jpg',
        'so_trang'    => 320,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 95,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Cha Giàu Cha Nghèo',
        'tac_gia'     => ['Robert Kiyosaki'],
        'gia'         => 108000,
        'mo_ta'       => 'Cha Giàu Cha Nghèo (Rich Dad Poor Dad) là cuốn sách tài chính cá nhân bán chạy nhất thế giới của Robert Kiyosaki, thay đổi cách nhìn về tiền bạc và đầu tư.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781612680194-L.jpg',
        'so_trang'    => 336,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 150,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Nghĩ Giàu Làm Giàu',
        'tac_gia'     => ['Napoleon Hill'],
        'gia'         => 98000,
        'mo_ta'       => 'Nghĩ Giàu Làm Giàu (Think and Grow Rich) của Napoleon Hill là cuốn sách self-help kinh điển nhất mọi thời đại về bí quyết thành công.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781585424337-L.jpg',
        'so_trang'    => 272,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 130,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Điểm Bùng Phát',
        'tac_gia'     => ['Malcolm Gladwell'],
        'gia'         => 108000,
        'mo_ta'       => 'Điểm Bùng Phát (The Tipping Point) của Malcolm Gladwell phân tích những yếu tố khiến ý tưởng, sản phẩm và xu hướng xã hội bùng phát và lan truyền.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780316346627-L.jpg',
        'so_trang'    => 304,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 80,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Ngoại Hạng',
        'tac_gia'     => ['Malcolm Gladwell'],
        'gia'         => 112000,
        'mo_ta'       => 'Ngoại Hạng (Outliers) của Malcolm Gladwell khám phá bí mật của những người thành công xuất sắc và những yếu tố bị bỏ qua dẫn đến thành công.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780316017923-L.jpg',
        'so_trang'    => 336,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 85,
        'the_loai_id' => 2,
        'nxb_id'      => $nxbAlpha,
    ],
    // ---- Kỹ năng sống (the_loai_id=3) ----
    [
        'tieu_de'     => 'Thức Dậy Lúc 5 Giờ Sáng',
        'tac_gia'     => ['Robin Sharma'],
        'gia'         => 108000,
        'mo_ta'       => 'Thức Dậy Lúc 5 Giờ Sáng (The 5 AM Club) của Robin Sharma hướng dẫn phương pháp làm chủ buổi sáng để tối đa hóa năng suất và hạnh phúc.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781443456623-L.jpg',
        'so_trang'    => 384,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 100,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Atomic Habits',
        'tac_gia'     => ['James Clear'],
        'gia'         => 128000,
        'mo_ta'       => 'Atomic Habits của James Clear là cuốn sách về khoa học xây dựng thói quen tốt và phá bỏ thói quen xấu thông qua các thay đổi nhỏ nhưng bền vững.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg',
        'so_trang'    => 320,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 200,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Bí Quyết Tư Duy Thành Công',
        'tac_gia'     => ['Brian Tracy'],
        'gia'         => 95000,
        'mo_ta'       => 'Bí Quyết Tư Duy Thành Công của Brian Tracy chia sẻ các chiến lược và phương pháp tư duy giúp bạn đạt được mục tiêu và thành công trong cuộc sống.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780749927332-L.jpg',
        'so_trang'    => 272,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 80,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Sức Mạnh Của Thói Quen',
        'tac_gia'     => ['Charles Duhigg'],
        'gia'         => 112000,
        'mo_ta'       => 'Sức Mạnh Của Thói Quen (The Power of Habit) của Charles Duhigg giải thích khoa học đằng sau thói quen và cách thay đổi chúng để cải thiện cuộc sống.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780679603856-L.jpg',
        'so_trang'    => 371,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 90,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => '7 Thói Quen Hiệu Quả',
        'tac_gia'     => ['Stephen R. Covey'],
        'gia'         => 128000,
        'mo_ta'       => '7 Thói Quen Hiệu Quả (The 7 Habits of Highly Effective People) của Stephen Covey là một trong những cuốn sách phát triển bản thân ảnh hưởng nhất thế kỷ 20.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780743269513-L.jpg',
        'so_trang'    => 432,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 120,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbFirstNews,
    ],
    [
        'tieu_de'     => 'Đừng Bao Giờ Đi Ăn Một Mình',
        'tac_gia'     => ['Keith Ferrazzi'],
        'gia'         => 108000,
        'mo_ta'       => 'Đừng Bao Giờ Đi Ăn Một Mình (Never Eat Alone) của Keith Ferrazzi hướng dẫn nghệ thuật xây dựng mạng lưới quan hệ và kết nối con người.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780385512060-L.jpg',
        'so_trang'    => 352,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 70,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Chủ Nghĩa Khắc Kỷ',
        'tac_gia'     => ['Ryan Holiday'],
        'gia'         => 98000,
        'mo_ta'       => 'Chủ Nghĩa Khắc Kỷ (The Obstacle Is the Way) của Ryan Holiday áp dụng triết học Khắc kỷ cổ đại vào cuộc sống hiện đại để vượt qua thách thức.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781591846857-L.jpg',
        'so_trang'    => 224,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 85,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Ego Là Kẻ Thù',
        'tac_gia'     => ['Ryan Holiday'],
        'gia'         => 98000,
        'mo_ta'       => 'Ego Là Kẻ Thù (Ego Is the Enemy) của Ryan Holiday phân tích cách cái tôi ngăn cản chúng ta đạt được tiềm năng thực sự và cách kiểm soát nó.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781591847816-L.jpg',
        'so_trang'    => 256,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 80,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Deep Work',
        'tac_gia'     => ['Cal Newport'],
        'gia'         => 112000,
        'mo_ta'       => 'Deep Work của Cal Newport là kim chỉ nam về công việc sâu - khả năng tập trung không phân tâm vào những nhiệm vụ nhận thức đòi hỏi cao.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781455586691-L.jpg',
        'so_trang'    => 304,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 95,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbAlpha,
    ],
    [
        'tieu_de'     => 'Dám Bị Ghét',
        'tac_gia'     => ['Ichiro Kishimi'],
        'gia'         => 108000,
        'mo_ta'       => 'Dám Bị Ghét (The Courage to Be Disliked) là cuốn sách triết học tâm lý học dựa trên tư tưởng của Alfred Adler, giúp bạn thoát khỏi nỗi sợ hãi và sống tự do.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781501197277-L.jpg',
        'so_trang'    => 288,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 110,
        'the_loai_id' => 3,
        'nxb_id'      => $nxbFirstNews,
    ],
    // ---- Công nghệ thông tin (the_loai_id=4) ----
    [
        'tieu_de'     => 'The Pragmatic Programmer',
        'tac_gia'     => ['Andrew Hunt'],
        'gia'         => 185000,
        'mo_ta'       => 'The Pragmatic Programmer là cuốn sách lập trình kinh điển cung cấp những lời khuyên thực tế cho các lập trình viên muốn trở nên chuyên nghiệp hơn.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780135957059-L.jpg',
        'so_trang'    => 352,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 60,
        'the_loai_id' => 4,
        'nxb_id'      => $nxbThongTin,
    ],
    [
        'tieu_de'     => 'Design Patterns',
        'tac_gia'     => ['CLRS'],
        'gia'         => 215000,
        'mo_ta'       => 'Design Patterns: Elements of Reusable Object-Oriented Software (Gang of Four) là cuốn sách kinh điển về các mẫu thiết kế phần mềm hướng đối tượng.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780201633610-L.jpg',
        'so_trang'    => 416,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 50,
        'the_loai_id' => 4,
        'nxb_id'      => $nxbThongTin,
    ],
    [
        'tieu_de'     => 'Cracking the Coding Interview',
        'tac_gia'     => ['Gayle McDowell'],
        'gia'         => 245000,
        'mo_ta'       => 'Cracking the Coding Interview của Gayle McDowell là cuốn sách cẩm nang không thể thiếu để chuẩn bị cho các phỏng vấn kỹ thuật tại Google, Amazon, Facebook.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780984782857-L.jpg',
        'so_trang'    => 696,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 75,
        'the_loai_id' => 4,
        'nxb_id'      => $nxbThongTin,
    ],
    [
        'tieu_de'     => 'Introduction to Algorithms',
        'tac_gia'     => ['CLRS'],
        'gia'         => 285000,
        'mo_ta'       => 'Introduction to Algorithms (CLRS) là giáo trình thuật toán kinh điển nhất thế giới, được sử dụng rộng rãi trong các trường đại học hàng đầu.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780262033848-L.jpg',
        'so_trang'    => 1312,
        'nam_xuat_ban'=> 2022,
        'so_luong_ton'=> 40,
        'the_loai_id' => 4,
        'nxb_id'      => $nxbGiaoDuc,
    ],
    [
        'tieu_de'     => "You Don't Know JS",
        'tac_gia'     => ['Kyle Simpson'],
        'gia'         => 155000,
        'mo_ta'       => "You Don't Know JS của Kyle Simpson là bộ sách chuyên sâu về JavaScript, giải thích các cơ chế hoạt động bên trong của ngôn ngữ lập trình phổ biến này.",
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9781491904244-L.jpg',
        'so_trang'    => 358,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 65,
        'the_loai_id' => 4,
        'nxb_id'      => $nxbThongTin,
    ],
    // ---- Thiếu nhi (the_loai_id=5) ----
    [
        'tieu_de'     => 'Sherlock Holmes Toàn Tập',
        'tac_gia'     => ['Arthur Conan Doyle'],
        'gia'         => 165000,
        'mo_ta'       => 'Sherlock Holmes Toàn Tập tập hợp tất cả các câu chuyện về thám tử thiên tài Sherlock Holmes và người bạn Watson trong những vụ án huyền bí.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780192837332-L.jpg',
        'so_trang'    => 1059,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 70,
        'the_loai_id' => 5,
        'nxb_id'      => $nxbKimDong,
    ],
    [
        'tieu_de'     => 'Những Cuộc Phiêu Lưu Của Tom Sawyer',
        'tac_gia'     => ['Mark Twain'],
        'gia'         => 68000,
        'mo_ta'       => 'Những Cuộc Phiêu Lưu Của Tom Sawyer là tiểu thuyết kinh điển của Mark Twain, kể về tuổi thơ hồn nhiên và những cuộc phiêu lưu thú vị của cậu bé Tom Sawyer.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780143039563-L.jpg',
        'so_trang'    => 288,
        'nam_xuat_ban'=> 2019,
        'so_luong_ton'=> 85,
        'the_loai_id' => 5,
        'nxb_id'      => $nxbKimDong,
    ],
    [
        'tieu_de'     => 'Oliver Twist',
        'tac_gia'     => ['Charles Dickens'],
        'gia'         => 88000,
        'mo_ta'       => 'Oliver Twist là tiểu thuyết nổi tiếng của Charles Dickens về cậu bé mồ côi Oliver trong xã hội Anh thế kỷ 19 đầy bất công và nghèo khổ.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780141439495-L.jpg',
        'so_trang'    => 480,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 60,
        'the_loai_id' => 5,
        'nxb_id'      => $nxbKimDong,
    ],
    // ---- Kinh dị (the_loai_id=15) ----
    [
        'tieu_de'     => 'The Shining',
        'tac_gia'     => ['Stephen King'],
        'gia'         => 128000,
        'mo_ta'       => 'The Shining là tiểu thuyết kinh dị kinh điển của Stephen King về gia đình Torrance và những bí ẩn rùng rợn tại khách sạn Overlook cô lập trong mùa đông.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780307743657-L.jpg',
        'so_trang'    => 659,
        'nam_xuat_ban'=> 2021,
        'so_luong_ton'=> 55,
        'the_loai_id' => 15,
        'nxb_id'      => $nxbTre,
    ],
    [
        'tieu_de'     => 'Giết Con Chim Nhại',
        'tac_gia'     => ['Harper Lee'],
        'gia'         => 108000,
        'mo_ta'       => 'Giết Con Chim Nhại (To Kill a Mockingbird) là tiểu thuyết đoạt giải Pulitzer của Harper Lee, câu chuyện về bình đẳng và công lý ở miền Nam nước Mỹ thập niên 1930.',
        'anh_bia'     => 'https://covers.openlibrary.org/b/isbn/9780061743528-L.jpg',
        'so_trang'    => 336,
        'nam_xuat_ban'=> 2020,
        'so_luong_ton'=> 70,
        'the_loai_id' => 15,
        'nxb_id'      => $nxbHoiNhaVan,
    ],
];

// Get existing book titles to avoid duplicates
$existingTitles = $pdo->query("SELECT tieu_de FROM sach")->fetchAll(PDO::FETCH_COLUMN);
$existingTitles = array_map('mb_strtolower', $existingTitles);

$stmtInsertBook = $pdo->prepare("
    INSERT INTO sach (tieu_de, gia, mo_ta, anh_bia, so_trang, nam_xuat_ban, so_luong_ton, the_loai_id, nxb_id, dang_ban, is_flash_sale)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0)
");
$stmtInsertAuthorLink = $pdo->prepare("INSERT IGNORE INTO sach_tac_gia (sach_id, tac_gia_id) VALUES (?, ?)");

$addedCount = 0;
$skippedCount = 0;
$addedTitles = [];

foreach ($books as $book) {
    $titleLower = mb_strtolower($book['tieu_de']);
    if (in_array($titleLower, $existingTitles)) {
        echo "   SKIP (duplicate): {$book['tieu_de']}" . PHP_EOL;
        $skippedCount++;
        continue;
    }

    try {
        $stmtInsertBook->execute([
            $book['tieu_de'],
            $book['gia'],
            $book['mo_ta'],
            $book['anh_bia'],
            $book['so_trang'],
            $book['nam_xuat_ban'],
            $book['so_luong_ton'],
            $book['the_loai_id'],
            $book['nxb_id'],
        ]);
        $bookId = (int)$pdo->lastInsertId();

        // Link authors
        foreach ($book['tac_gia'] as $authorName) {
            if (isset($authorMap[$authorName])) {
                $stmtInsertAuthorLink->execute([$bookId, $authorMap[$authorName]]);
            } else {
                echo "   WARNING: Author not found in map: $authorName" . PHP_EOL;
            }
        }

        echo "   ADDED [{$book['the_loai_id']}] #{$bookId}: {$book['tieu_de']}" . PHP_EOL;
        $addedCount++;
        $addedTitles[] = $book['tieu_de'];
        $existingTitles[] = $titleLower; // Prevent duplicate within same run
    } catch (Exception $e) {
        echo "   ERROR adding {$book['tieu_de']}: " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL;
echo "=== KẾT QUẢ MIGRATION ===" . PHP_EOL;
echo "Sách đã thêm: $addedCount" . PHP_EOL;
echo "Sách bỏ qua (trùng): $skippedCount" . PHP_EOL;
echo PHP_EOL;

// Verify totals
$total = (int)$pdo->query("SELECT COUNT(*) FROM sach")->fetchColumn();
$totalNxb = (int)$pdo->query("SELECT COUNT(*) FROM nha_xuat_ban")->fetchColumn();
$totalTg = (int)$pdo->query("SELECT COUNT(*) FROM tac_gia")->fetchColumn();
$flashSaleExists = (int)$pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='book_store' AND table_name='flash_sale'")->fetchColumn();

echo "Tổng sách trong DB: $total" . PHP_EOL;
echo "Tổng NXB trong DB: $totalNxb" . PHP_EOL;
echo "Tổng tác giả trong DB: $totalTg" . PHP_EOL;
echo "Bảng flash_sale tồn tại: " . ($flashSaleExists ? 'YES' : 'NO') . PHP_EOL;
echo PHP_EOL . "DONE!" . PHP_EOL;
