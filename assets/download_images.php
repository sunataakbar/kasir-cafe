<?php
// Jalankan ini sekali jika ingin menyimpan gambar placeholder ke folder assets/images
// Pastikan folder assets/images sudah ada dan dapat ditulis
$dir = __DIR__ . '/images';
if (!is_dir($dir)) mkdir($dir, 0755, true);

for ($i=1;$i<=30;$i++) {
    $name = "menu{$i}.jpg";
    $url = "https://via.placeholder.com/600x400?text=Menu+{$i}";
    $img = file_get_contents($url);
    if ($img) {
        file_put_contents($dir . '/' . $name, $img);
        echo "Saved $name\n";
    } else {
        echo "Failed $name\n";
    }
}
echo "Selesai\n";
?>
