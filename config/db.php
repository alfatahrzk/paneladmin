<?php

// --- Konfigurasi Kredensial Database ---
// define('DB_HOST', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'webpanel');
// define('DB_CHARSET', 'utf8mb4');

// local
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'webpanel');
define('DB_CHARSET', 'utf8mb4');

// --- Membuat Koneksi ---
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// --- Cek Koneksi ---
if ($conn->connect_error) {
    // Sebaiknya log error ini di production dan tampilkan pesan umum ke user
    error_log("Koneksi Database Gagal: " . $conn->connect_error);
    die("Koneksi ke database gagal. Silakan coba beberapa saat lagi.");
}

// --- Atur Character Set ---
if (!$conn->set_charset(DB_CHARSET)) {
    error_log("Error loading character set " . DB_CHARSET . ": " . $conn->error);
    // Pertimbangkan untuk menghentikan skrip atau menangani error ini
}

// --- Atur Timezone Default PHP ---
date_default_timezone_set('Asia/Jakarta');

// Komentar jika koneksi berhasil sudah di-nonaktifkan, yang mana baik untuk produksi
// echo "Connected successfully";

// Variabel $conn sekarang siap digunakan di file lain yang meng-include file ini.
