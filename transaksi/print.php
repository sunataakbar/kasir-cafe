<?php
require '../config/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo "ID transaksi tidak valid";
    exit;
}
$stmt = $koneksi->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$trans = $res->fetch_assoc();
$stmt->close();

$stmt2 = $koneksi->prepare("SELECT * FROM detail_transaksi WHERE id_transaksi = ?");
$stmt2->bind_param('i', $id);
$stmt2->execute();
$res2 = $stmt2->get_result();
$details = $res2->fetch_all(MYSQLI_ASSOC);
$stmt2->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Struk #<?php echo $id; ?></title>
  <style>
    body { font-family: monospace; width:300px; padding:10px; }
    .center { text-align:center; }
    table { width:100%; }
    td, th { font-size:12px; }
  </style>
</head>
<body onload="window.print();">
  <div class="center">
    <h3>CAFE SEDERHANA</h3>
    <div><?php echo $trans['tanggal']; ?></div>
  </div>
  <hr>
  <div>Nama: <?php echo htmlspecialchars($trans['nama_pelanggan']); ?></div>
  <table>
    <?php foreach ($details as $d): ?>
      <tr>
        <td><?php echo htmlspecialchars($d['nama_menu']); ?></td>
        <td style="text-align:right"><?php echo $d['jumlah']; ?> x</td>
        <td style="text-align:right">Rp <?php echo number_format($d['subtotal'],0,',','.'); ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <hr>
  <div style="display:flex; justify-content:space-between;">
    <strong>Total</strong>
    <strong>Rp <?php echo number_format($trans['total_harga'],0,',','.'); ?></strong>
  </div>
  <hr>
  <div class="center">Terima kasih, selamat datang kembali!</div>
</body>
</html>
