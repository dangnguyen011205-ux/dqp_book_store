<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';

$db = Core\Database::getInstance()->getConnection();

$seeds = [
    [
        'user_id' => 3,
        'book_id' => 1,
        'star'    => 5,
        'status'  => 'cho_duyet',
        'content' => 'Sách của bác Ánh lúc nào cũng mượt mà, gợi lại tuổi thơ ngọt ngào. Bìa in sắc nét, gáy đóng chỉ rất chắc chắn!'
    ],
    [
        'user_id' => 4,
        'book_id' => 4,
        'star'    => 5,
        'status'  => 'cho_duyet',
        'content' => 'Cuốn sách gối đầu giường của mọi thế hệ. Bản dịch mới rất dễ hiểu và thực tế. Đóng gói cẩn thận 3 lớp chống sốc.'
    ],
    [
        'user_id' => 9,
        'book_id' => 5,
        'star'    => 4,
        'status'  => 'cho_duyet',
        'content' => 'Sách gối đầu giường cho dân dev, giải thích cặn kẽ về clean code và refactoring. Trừ 1 sao vì gáy sách hơi cứng khi mở phẳng.'
    ],
    [
        'user_id' => 3,
        'book_id' => 6,
        'star'    => 5,
        'status'  => 'hien_thi',
        'content' => 'Hành trình theo đuổi ước mơ của Santiago truyền cảm hứng rất lớn. Giao hàng hỏa tốc trong 2 giờ rất ấn tượng.'
    ],
    [
        'user_id' => 4,
        'book_id' => 2,
        'star'    => 1,
        'status'  => 'an',
        'content' => 'Giao sai địa chỉ, shipper thái độ không tốt và sách bị rách bìa góc ngoài.'
    ],
    [
        'user_id' => 9,
        'book_id' => 6,
        'star'    => 5,
        'status'  => 'hien_thi',
        'content' => 'Bản in rất đẹp, giấy ngà vàng Phần Lan đọc không mỏi mắt.'
    ]
];

foreach ($seeds as $s) {
    $exists = $db->query("SELECT id FROM danh_gia WHERE nguoi_dung_id = {$s['user_id']} AND sach_id = {$s['book_id']}")->fetch();
    if (!$exists) {
        $stmt = $db->prepare("INSERT INTO danh_gia (nguoi_dung_id, sach_id, so_sao, noi_dung, trang_thai, ngay_tao) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$s['user_id'], $s['book_id'], $s['star'], $s['content'], $s['status']]);
        echo "Inserted review for User {$s['user_id']} - Book {$s['book_id']}\n";
    } else {
        echo "Review for User {$s['user_id']} - Book {$s['book_id']} already exists\n";
    }
}

echo "Done seeding reviews.\n";
