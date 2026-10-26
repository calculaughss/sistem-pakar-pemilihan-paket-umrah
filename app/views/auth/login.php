<?php
$pageTitle = 'Login — SPK Umrah Persada Al-Amin';
$bodyClass = 'auth-page';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <meta name="base-url" content="<?= BASE_URL ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="icon">☪</div>
      <h2>Persada Al-Amin</h2>
      <p style="color:var(--text-muted);font-size:0.9rem;">Masuk ke akun Anda</p>
    </div>

    <?php if ($msg = Session::getFlash('error')): ?>
      <div class="alert alert-danger" data-dismiss>⚠ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('success')): ?>
      <div class="alert alert-success" data-dismiss>✓ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="nama@email.com" required autocomplete="email">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control"
               placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;justify-content:center;margin-top:8px;">
        Masuk
      </button>
    </form>

    <p class="text-center mt-2" style="font-size:0.88rem;color:var(--text-muted);">
      Belum punya akun?
      <a href="<?= BASE_URL ?>/register" style="color:var(--green);font-weight:600;">Daftar sekarang</a>
    </p>
    <p class="text-center mt-1" style="font-size:0.85rem;">
      <a href="<?= BASE_URL ?>/" style="color:var(--text-muted);">← Kembali ke Beranda</a>
    </p>
  </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>