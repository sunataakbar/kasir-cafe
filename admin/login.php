<?php
session_start();
require '../config/koneksi.php';

if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $koneksi->prepare("SELECT id_admin, password FROM admin WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id_admin, $hash);
    if ($stmt->fetch()) {
        if (password_verify($password, $hash)) {
            $_SESSION['admin'] = $id_admin;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $err = 'Username atau password salah.';
        }
    } else {
        $err = 'Username atau password salah.';
    }
    $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login Admin - Kasir Cafe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background:#f7f7f7; }
    .login-box { max-width:420px; margin:80px auto; }
  </style>
</head>
<body>
  <div class="login-box card p-4">
    <h4 class="mb-3">Login Admin</h4>
    <?php if ($err): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input class="form-control" name="username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="d-grid">
        <button class="btn btn-primary">Login</button>
      </div>
    </form>
    <hr>
    <small>Jika belum membuat admin: jalankan <code>setup_admin.php</code> sekali.</small>
  </div>
</body>
</html>
