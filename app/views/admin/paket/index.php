<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Kelola Paket — Admin SPK Umrah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Paket Umrah</div>
    <div class="page-subtitle">Kelola data paket umrah yang tersedia</div>

    <?php if ($msg = Session::getFlash('success')): ?>
      <div class="alert alert-success" data-dismiss>✓ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="table-wrap">
      <div class="table-header">
        <h3>Daftar Paket (<?= count($paketList) ?>)</h3>
        <a href="<?= BASE_URL ?>/admin/paket/tambah" class="btn btn-sm btn-green">+ Tambah Paket</a>
      </div>
      <table>
        <thead>
          <tr><th>#</th><th>Kode</th><th>Nama Paket</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Bintang</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php foreach ($paketList as $i => $p): ?>
            <tr>
              <td style="color:var(--text-muted)"><?= $i + 1 ?></td>
              <td><code><?= htmlspecialchars($p['kode_paket']) ?></code></td>
              <td><strong><?= htmlspecialchars($p['nama_paket']) ?></strong></td>
              <td><span class="paket-badge badge-<?= $p['kategori'] ?>"><?= strtoupper($p['kategori']) ?></span></td>
              <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
              <td><?= $p['durasi_hari'] ?> hari</td>
              <td class="stars"><?= str_repeat('★', $p['bintang_hotel']) ?></td>
              <td>
                <a href="<?= BASE_URL ?>/admin/paket/edit/<?= $p['id'] ?>" class="btn btn-sm btn-green">Edit</a>
                <button onclick="konfirmasiHapus('<?= BASE_URL ?>/admin/paket/hapus/<?= $p['id'] ?>','<?= htmlspecialchars($p['nama_paket']) ?>')" class="btn btn-sm btn-danger">Hapus</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>