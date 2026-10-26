<?php
$pageTitle = 'Dashboard Admin — SPK Umrah';
Session::requireAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= $pageTitle ?></title>
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
    <div class="page-title">Dashboard</div>
    <div class="page-subtitle">Selamat datang, <?= htmlspecialchars(Session::get('nama')) ?></div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div><div class="stat-value"><?= $stats['total_paket'] ?></div><div class="stat-label">Total Paket</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">❓</div>
        <div><div class="stat-value"><?= $stats['total_kriteria'] ?></div><div class="stat-label">Kriteria</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div><div class="stat-value"><?= $stats['total_aturan'] ?></div><div class="stat-label">Basis Aturan</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div><div class="stat-value"><?= $stats['total_konsultasi'] ?></div><div class="stat-label">Konsultasi</div></div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div><div class="stat-value"><?= $stats['konsultasi_selesai'] ?></div><div class="stat-label">Selesai</div></div>
      </div>
    </div>

    <!-- Konsultasi Terbaru -->
    <div class="table-wrap">
      <div class="table-header">
        <h3>Konsultasi Terbaru</h3>
        <a href="<?= BASE_URL ?>/admin/laporan" class="btn btn-sm btn-green">Lihat Semua</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Kode</th><th>Nama</th><th>No HP</th><th>Status</th><th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($konsultasiTerbaru as $k): ?>
            <tr>
              <td><code><?= htmlspecialchars($k['kode_konsultasi']) ?></code></td>
              <td><?= htmlspecialchars($k['nama_konsultan'] ?? $k['nama_user'] ?? '—') ?></td>
              <td><?= htmlspecialchars($k['no_hp'] ?? '—') ?></td>
              <td>
                <span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;
                  background:<?= $k['status']==='selesai' ? '#EAFAF1' : '#FFF3DC' ?>;
                  color:<?= $k['status']==='selesai' ? '#1E8449' : '#9A5D00' ?>;">
                  <?= ucfirst($k['status']) ?>
                </span>
              </td>
              <td style="color:var(--text-muted);font-size:0.85rem;"><?= date('d M Y H:i', strtotime($k['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($konsultasiTerbaru)): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px;">Belum ada data konsultasi.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>