<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Laporan Konsultasi — Admin SPK Umrah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Riwayat Konsultasi</div>
    <div class="page-subtitle">Semua sesi konsultasi yang telah dilakukan oleh pengguna</div>

    <div class="table-wrap">
      <div class="table-header">
        <h3>Total: <?= count($konsultasiList) ?> konsultasi</h3>
        <button onclick="window.print()" class="btn btn-sm" style="border:1.5px solid var(--border);color:var(--green);">🖨 Cetak</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Status</th>
            <th>Rekomendasi</th>
            <th>Skor</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($konsultasiList as $i => $k):
            // Ambil hasil untuk setiap konsultasi
            $konsultasiModel = new Konsultasi();
            $hasilRow = $konsultasiModel->getHasil($k['id']);
          ?>
            <tr>
              <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
              <td><code style="font-size:0.78rem;"><?= htmlspecialchars($k['kode_konsultasi']) ?></code></td>
              <td><?= htmlspecialchars($k['nama_konsultan'] ?? $k['nama_user'] ?? '—') ?></td>
              <td style="color:var(--text-muted);"><?= htmlspecialchars($k['no_hp'] ?? '—') ?></td>
              <td>
                <span style="padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;
                  background:<?= $k['status']==='selesai' ? '#EAFAF1' : '#FFF3DC' ?>;
                  color:<?= $k['status']==='selesai' ? '#1E8449' : '#9A5D00' ?>;">
                  <?= ucfirst($k['status']) ?>
                </span>
              </td>
              <td>
                <?php if ($hasilRow): ?>
                  <span style="font-size:0.85rem;"><?= htmlspecialchars($hasilRow['nama_paket']) ?></span>
                <?php else: ?>
                  <span style="color:var(--text-muted);font-size:0.85rem;">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($hasilRow): ?>
                  <span style="font-weight:700;color:var(--gold-dark);"><?= number_format($hasilRow['skor'],1) ?>%</span>
                <?php else: ?>—<?php endif; ?>
              </td>
              <td style="color:var(--text-muted);font-size:0.82rem;">
                <?= date('d M Y H:i', strtotime($k['created_at'])) ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($konsultasiList)): ?>
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">Belum ada data konsultasi.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>