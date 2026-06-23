<?php
require '../config/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { echo "ID tidak valid"; exit; }
$stmt = $koneksi->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$trans = $res->fetch_assoc();
$stmt->close();

$stmt2 = $koneksi->prepare("SELECT * FROM detail_transaksi WHERE id_transaksi = ?");
$stmt2->bind_param('i', $id);
$stmt2->execute();
$details = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Detail Transaksi #<?php echo $id; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
  <div class="container">
    <h4>Detail Transaksi #<?php echo $id; ?></h4>
    <div>Tanggal: <?php echo $trans['tanggal']; ?></div>
    <div>Nama: <?php echo htmlspecialchars($trans['nama_pelanggan']); ?></div>
    <table class="table table-sm mt-2">
      <thead><tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php foreach ($details as $d): ?>
          <tr>
            <td><?php echo htmlspecialchars($d['nama_menu']); ?></td>
            <td><?php echo $d['jumlah']; ?></td>
            <td>Rp <?php echo number_format($d['subtotal'],0,',','.'); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="mt-2"><strong>Total: Rp <?php echo number_format($trans['total_harga'],0,',','.'); ?></strong></div>
    <a href="history.php" class="btn btn-secondary mt-3">Kembali</a>
  </div>
</body>
</html>
