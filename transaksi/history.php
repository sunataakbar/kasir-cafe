<?php
require '../config/koneksi.php';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$where = '';
$params = [];
if ($from && $to) {
    $where = "WHERE tanggal BETWEEN ? AND ?";
    $fromdt = $from . ' 00:00:00';
    $todt = $to . ' 23:59:59';
    $stmt = $koneksi->prepare("SELECT * FROM transaksi $where ORDER BY tanggal DESC");
    $stmt->bind_param('ss', $fromdt, $todt);
} else {
    $stmt = $koneksi->prepare("SELECT * FROM transaksi ORDER BY tanggal DESC LIMIT 200");
}
$stmt->execute();
$res = $stmt->get_result();
$trans = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Riwayat Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-3">
  <div class="container">
    <h4>Riwayat Transaksi</h4>
    <form class="row g-2 mb-3" method="get">
      <div class="col-auto">
        <input type="date" name="from" class="form-control" value="<?php echo htmlspecialchars($from); ?>">
      </div>
      <div class="col-auto">
        <input type="date" name="to" class="form-control" value="<?php echo htmlspecialchars($to); ?>">
      </div>
      <div class="col-auto">
        <button class="btn btn-primary">Filter</button>
      </div>
      <div class="col-auto">
        <a href="history.php" class="btn btn-secondary">Reset</a>
      </div>
      <div class="col-auto ms-auto">
        <a href="../admin/dashboard.php" class="btn btn-outline-primary">Kembali ke Dashboard</a>
      </div>
    </form>

    <table class="table table-striped table-sm">
      <thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Total</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($trans as $t): ?>
        <tr>
          <td><?php echo $t['id_transaksi']; ?></td>
          <td><?php echo $t['tanggal']; ?></td>
          <td><?php echo htmlspecialchars($t['nama_pelanggan']); ?></td>
          <td>Rp <?php echo number_format($t['total_harga'],0,',','.'); ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="print.php?id=<?php echo $t['id_transaksi']; ?>" target="_blank">Cetak</a>
            <a class="btn btn-sm btn-outline-info" href="detail.php?id=<?php echo $t['id_transaksi']; ?>">Detail</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
