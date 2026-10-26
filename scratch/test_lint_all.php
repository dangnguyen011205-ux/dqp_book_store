<?php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/..');
$it = new RecursiveIteratorIterator($dir);
$errors = 0;
$checked = 0;

foreach ($it as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        if (strpos($path, 'stitch_') !== false || strpos($path, 'vendor') !== false) {
            continue;
        }
        $out = [];
        $code = 0;
        exec('php -l ' . escapeshellarg($path), $out, $code);
        if ($code !== 0) {
            echo "LINT ERROR: " . $path . "\n" . implode("\n", $out) . "\n";
            $errors++;
        }
        $checked++;
    }
}

echo "Lint completed. Checked $checked files. Total errors: $errors\n";
if ($errors > 0) exit(1);
