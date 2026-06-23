<?php
// config/koneksi.php
// Sesuaikan username/password/database sesuai XAMPP (default: root, no password)
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'cafe_kasir';

$koneksi = new mysqli($host, $user, $pass, $dbname);
if ($koneksi->connect_error) {
    die('Koneksi database gagal: ' . $koneksi->connect_error);
}
$koneksi->set_charset("utf8mb4");
?>
