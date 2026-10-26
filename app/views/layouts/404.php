<?php $pageTitle = '404 — Halaman Tidak Ditemukan'; ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $pageTitle ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:40px;">
  <div>
    <div style="font-size:5rem;margin-bottom:16px;">🕌</div>
    <h1 style="font-family:var(--ff-serif);font-size:4rem;color:var(--green);line-height:1;">404</h1>
    <h2 style="font-family:var(--ff-serif);color:var(--text-muted);font-weight:400;margin:8px 0 20px;">Halaman tidak ditemukan</h2>
    <a href="<?= BASE_URL ?>/" class="btn btn-green">← Kembali ke Beranda</a>
  </div>
</div>
</body></html>
