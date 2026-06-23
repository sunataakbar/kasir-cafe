<?php
// transaksi/simpan.php
require '../config/koneksi.php';

// Menerima JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    echo json_encode(['success'=>false, 'error'=>'Data tidak valid']);
    exit;
}
$customer = trim($data['customer'] ?? '');
$items = $data['items'] ?? [];

if ($customer === '' || !is_array($items) || count($items) === 0) {
    echo json_encode(['success'=>false, 'error'=>'Data tidak lengkap']);
    exit;
}

// Hitung total
$total = 0;
foreach ($items as $it) {
    $harga = (int)$it['harga'];
    $jumlah = (int)$it['jumlah'];
    $total += $harga * $jumlah;
}

// Mulai transaksi
$koneksi->begin_transaction();
try {
    $stmt = $koneksi->prepare("INSERT INTO transaksi (tanggal, nama_pelanggan, total_harga) VALUES (NOW(), ?, ?)");
    $stmt->bind_param('si', $customer, $total);
    $stmt->execute();
    $id_transaksi = $stmt->insert_id;
    $stmt->close();

    $stmt2 = $koneksi->prepare("INSERT INTO detail_transaksi (id_transaksi, nama_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)");
    foreach ($items as $it) {
        $nama = $it['nama'];
        $jumlah = (int)$it['jumlah'];
        $subtotal = (int)$it['harga'] * $jumlah;
        $stmt2->bind_param('isii', $id_transaksi, $nama, $jumlah, $subtotal);
        $stmt2->execute();
    }
    $stmt2->close();

    $koneksi->commit();
    echo json_encode(['success'=>true, 'id'=>$id_transaksi]);
} catch (Exception $e) {
    $koneksi->rollback();
    echo json_encode(['success'=>false, 'error'=>$e->getMessage()]);
}
?>
