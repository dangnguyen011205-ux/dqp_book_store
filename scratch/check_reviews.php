<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Model.php';
require_once __DIR__ . '/../Models/Review.php';

$db = Core\Database::getInstance()->getConnection();
$users = $db->query('SELECT id, ho_ten, email FROM nguoi_dung')->fetchAll();
echo "--- USERS ---\n";
foreach ($users as $u) {
    echo "ID: {$u['id']} | {$u['ho_ten']} | {$u['email']}\n";
}

$books = $db->query('SELECT id, tieu_de FROM sach LIMIT 10')->fetchAll();
echo "\n--- BOOKS ---\n";
foreach ($books as $b) {
    echo "ID: {$b['id']} | {$b['tieu_de']}\n";
}
