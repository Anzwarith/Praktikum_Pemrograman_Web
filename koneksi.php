<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dbUnmulCare';

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, "utf8");

// Fungsi untuk mencegah SQL injection
function bersihkan_input($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, htmlspecialchars(strip_tags(trim($data))));
}
?>