<?php
// Jalankan sekali untuk membuat akun admin dengan password ter-hash.
// Letakkan di root proyek, akses via browser: http://localhost/yourproject/setup_admin.php
require 'config/koneksi.php';

$username = 'admin';
$password_plain = 'admin123'; // ganti bila mau
$hash = password_hash($password_plain, PASSWORD_DEFAULT);

// Cek apakah sudah ada admin
$stmt = $koneksi->prepare("SELECT id_admin FROM admin WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Admin sudah ada. Hapus baris admin dahulu atau ubah username.\n";
    exit;
}
$stmt->close();

$stmt = $koneksi->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param('ss', $username, $hash);
if ($stmt->execute()) {
    echo "Admin berhasil dibuat. Username: $username, Password: $password_plain\n";
    echo "Silakan hapus file setup_admin.php setelah selesai.\n";
} else {
    echo "Gagal membuat admin: " . $stmt->error;
}
$stmt->close();
?>
