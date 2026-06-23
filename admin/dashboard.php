<?php
session_start();
require '../config/koneksi.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Ambil menu (30)
$q = $koneksi->query("SELECT * FROM menu ORDER BY id_menu LIMIT 30");
$menus = $q->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard Kasir - Cafe</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Kasir Cafe</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-lg-8">
      <div class="row" id="menu-list">
        <?php foreach ($menus as $m): ?>
          <div class="col-sm-6 col-md-4 col-lg-4 mb-3">
            <div class="card h-100">
              <img src="<?php echo htmlspecialchars($m['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($m['nama_menu']); ?>" style="height:160px; object-fit:cover;">
              <div class="card-body d-flex flex-column">
                <h6 class="card-title"><?php echo htmlspecialchars($m['nama_menu']); ?></h6>
                <p class="card-text">Rp <?php echo number_format($m['harga'],0,',','.'); ?></p>
                <div class="mt-auto">
                  <button class="btn btn-sm btn-success btn-add" data-id="<?php echo $m['id_menu']; ?>" data-name="<?php echo htmlspecialchars($m['nama_menu']); ?>" data-price="<?php echo $m['harga']; ?>">Tambah</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          Keranjang
        </div>
        <div class="card-body">
          <div id="cart-empty-message">Keranjang kosong</div>
          <div id="cart-table" style="display:none;">
            <table class="table table-sm">
              <thead>
                <tr><th>Menu</th><th>Qty</th><th>Subtotal</th><th></th></tr>
              </thead>
              <tbody id="cart-body"></tbody>
            </table>
            <div class="d-flex justify-content-between">
              <strong>Total:</strong>
              <strong id="cart-total">Rp 0</strong>
            </div>
            <hr>
            <div class="mb-2">
              <input id="customer-name" class="form-control" placeholder="Nama pelanggan" />
            </div>
            <div class="d-grid gap-2">
              <button id="btn-save" class="btn btn-primary">Simpan Transaksi</button>
              <a href="../transaksi/history.php" class="btn btn-outline-secondary">Riwayat Transaksi</a>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-3 text-muted small">
        Tips: Tekan "Tambah" untuk menambah ke keranjang. Edit jumlah pada keranjang, lalu simpan.
      </div>
    </div>
  </div>
</div>

<script src="../assets/js/app.js"></script>
</body>
</html>
