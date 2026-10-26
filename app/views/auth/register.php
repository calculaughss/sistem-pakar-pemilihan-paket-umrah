<?php $pageTitle = 'Daftar — SPK Umrah Persada Al-Amin'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="icon">☪</div>
      <h2>Buat Akun</h2>
      <p style="color:var(--text-muted);font-size:0.9rem;">Daftar untuk menggunakan SPK Umrah</p>
    </div>

    <?php if ($msg = Session::getFlash('error')): ?>
      <div class="alert alert-danger" data-dismiss>⚠ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/register">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
      </div>
      <div class="form-group">
        <label>No. HP / WhatsApp</label>
        <input type="text" name="no_hp" class="form-control" placeholder="08xx-xxxx-xxxx">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required minlength="8">
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;justify-content:center;margin-top:8px;">
        Buat Akun
      </button>
    </form>

    <p class="text-center mt-2" style="font-size:0.88rem;color:var(--text-muted);">
      Sudah punya akun? <a href="<?= BASE_URL ?>/login" style="color:var(--green);font-weight:600;">Masuk</a>
    </p>
  </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>