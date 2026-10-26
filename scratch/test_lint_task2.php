<?php
$files = [
    __DIR__ . '/../Models/InventorySlip.php',
    __DIR__ . '/../Models/Book.php',
    __DIR__ . '/../Controllers/AdminBookController.php',
    __DIR__ . '/../Controllers/AdminInventoryController.php',
    __DIR__ . '/../Views/admin/books/index.php'
];

$errors = 0;
foreach ($files as $file) {
    $output = [];
    $returnVar = 0;
    exec("php -l " . escapeshellarg($file), $output, $returnVar);
    if ($returnVar !== 0) {
        echo "LINT ERROR in $file:\n" . implode("\n", $output) . "\n";
        $errors++;
    } else {
        echo "OK: " . basename($file) . "\n";
    }
}

if ($errors === 0) {
    echo "\nAll files passed PHP lint checks!\n";
} else {
    echo "\nTotal lint errors: $errors\n";
    exit(1);
}
