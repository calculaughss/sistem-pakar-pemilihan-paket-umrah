<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Basis Aturan — Admin SPK Umrah</title>
  <meta name="base-url" content="<?= BASE_URL ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Basis Aturan Forward Chaining</div>
    <div class="page-subtitle">Kelola IF–THEN rules untuk inferensi paket umrah</div>

    <?php if ($msg = Session::getFlash('success')): ?>
      <div class="alert alert-success" data-dismiss>✓ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="d-flex gap-2 mb-2" style="justify-content:flex-end;">
      <a href="<?= BASE_URL ?>/admin/aturan/tambah" class="btn btn-green">+ Tambah Aturan</a>
    </div>

    <!-- Daftar Aturan -->
    <?php foreach ($aturanList as $a): ?>
      <div class="card mb-2">
        <div class="card-body" style="padding:20px 24px;">
          <div class="d-flex align-center justify-between mb-2">
            <div>
              <span style="background:var(--green);color:var(--white);padding:3px 10px;border-radius:4px;font-size:0.8rem;font-weight:700;">
                <?= htmlspecialchars($a['kode_aturan']) ?>
              </span>
              <span style="margin-left:10px;font-size:0.85rem;color:var(--text-muted);">Bobot: <?= $a['bobot'] ?></span>
            </div>
            <button onclick="konfirmasiHapus('<?= BASE_URL ?>/admin/aturan/hapus/<?= $a['id'] ?>','<?= htmlspecialchars($a['kode_aturan']) ?>')"
                    class="btn btn-sm btn-danger">Hapus</button>
          </div>

          <!-- IF -->
          <div style="margin-bottom:10px;">
            <span style="font-weight:700;color:var(--gold-dark);font-size:0.85rem;">IF</span>
            <div style="margin-top:6px;display:flex;flex-wrap:wrap;gap:6px;">
              <?php foreach ($a['kondisi'] as $i => $kond): ?>
                <?php if ($i > 0): ?>
                  <span style="font-weight:700;color:var(--text-muted);align-self:center;font-size:0.8rem;">AND</span>
                <?php endif; ?>
                <span style="background:var(--cream-dark);border:1px solid var(--border);padding:4px 10px;border-radius:6px;font-size:0.82rem;">
                  <?= htmlspecialchars($kond['nama_kriteria']) ?> = <strong><?= htmlspecialchars($kond['nama_sub']) ?></strong>
                </span>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- THEN -->
          <div>
            <span style="font-weight:700;color:var(--green);font-size:0.85rem;">THEN</span>
            <span style="margin-left:8px;font-weight:600;">Rekomendasikan: <?= htmlspecialchars($a['nama_paket']) ?></span>
            <span class="paket-badge badge-<?= $a['kategori'] ?>" style="margin-left:8px;"><?= strtoupper($a['kategori']) ?></span>
          </div>

          <?php if ($a['deskripsi']): ?>
            <p style="margin-top:8px;font-size:0.83rem;color:var(--text-muted);"><?= htmlspecialchars($a['deskripsi']) ?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($aturanList)): ?>
      <div class="alert alert-info">Belum ada aturan. <a href="<?= BASE_URL ?>/admin/aturan/tambah">Tambah aturan pertama</a>.</div>
    <?php endif; ?>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>