<?php

$env_file = __DIR__ . DIRECTORY_SEPARATOR . '.env';

if (!file_exists($env_file)) {
    die('File .env tidak ditemukan: ' . $env_file);
}

$lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {

    $line = trim($line);

    // Skip komentar dan baris kosong
    if ($line === '' || strpos($line, '#') === 0) {
        continue;
    }

    // Pastikan ada tanda =
    if (strpos($line, '=') === false) {
        continue;
    }

    list($key, $value) = explode('=', $line, 2);

    $key = trim($key);
    $value = trim($value);

    // Hapus tanda petik jika ada
    $value = trim($value, "\"'");

    $_ENV[$key] = $value;
    putenv("$key=$value");
}

// Debug
//echo '<pre>';
//echo "ENV FILE: " . $env_file . PHP_EOL;
//print_r($_ENV);
//echo '</pre>';
//die();