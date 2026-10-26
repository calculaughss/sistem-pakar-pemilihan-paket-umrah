<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Kriteria — Admin SPK Umrah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Kriteria & Sub Kriteria</div>
    <div class="page-subtitle">Kelola pertanyaan konsultasi dan pilihan jawabannya</div>

    <?php if ($msg = Session::getFlash('success')): ?>
      <div class="alert alert-success" data-dismiss>✓ <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

      <!-- Form Tambah Kriteria -->
      <div class="card">
        <div class="card-header"><h3>Tambah Kriteria Baru</h3></div>
        <div class="card-body">
          <form method="POST" action="<?= BASE_URL ?>/admin/kriteria/simpan">
            <input type="hidden" name="tipe" value="kriteria">
            <div class="form-group">
              <label>Kode Kriteria</label>
              <input type="text" name="kode_kriteria" class="form-control" placeholder="K07" required>
            </div>
            <div class="form-group">
              <label>Nama Kriteria</label>
              <input type="text" name="nama_kriteria" class="form-control" placeholder="Jenis Kelamin" required>
            </div>
            <div class="form-group">
              <label>Pertanyaan</label>
              <textarea name="pertanyaan" class="form-control" rows="2" placeholder="Apa jenis kelamin Anda?" required></textarea>
            </div>
            <div class="form-group">
              <label>Urutan Tampil</label>
              <input type="number" name="urutan" class="form-control" value="<?= count($kriteriaList) + 1 ?>">
            </div>
            <button type="submit" class="btn btn-green">+ Tambah Kriteria</button>
          </form>
        </div>
      </div>

      <!-- Form Tambah Sub Kriteria -->
      <div class="card">
        <div class="card-header"><h3>Tambah Sub Kriteria</h3></div>
        <div class="card-body">
          <form method="POST" action="<?= BASE_URL ?>/admin/kriteria/simpan">
            <input type="hidden" name="tipe" value="sub">
            <div class="form-group">
              <label>Kriteria Induk</label>
              <select name="kriteria_id" class="form-control" required>
                <option value="">Pilih kriteria...</option>
                <?php foreach ($kriteriaList as $k): ?>
                  <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['kode_kriteria']) ?> — <?= htmlspecialchars($k['nama_kriteria']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Kode Sub</label>
              <input type="text" name="kode_sub" class="form-control" placeholder="K07_A" required>
            </div>
            <div class="form-group">
              <label>Nama Sub Kriteria</label>
              <input type="text" name="nama_sub" class="form-control" placeholder="Laki-laki" required>
            </div>
            <div class="form-group">
              <label>Keterangan (opsional)</label>
              <input type="text" name="keterangan" class="form-control" placeholder="Keterangan tambahan">
            </div>
            <button type="submit" class="btn btn-green">+ Tambah Sub</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Daftar Kriteria -->
    <div class="table-wrap mt-3">
      <div class="table-header"><h3>Daftar Kriteria (<?= count($kriteriaList) ?>)</h3></div>
      <?php foreach ($kriteriaList as $k): ?>
        <div style="padding:16px 24px;border-bottom:1px solid var(--border);">
          <div class="d-flex align-center justify-between">
            <div>
              <span style="background:var(--green);color:var(--white);padding:2px 8px;border-radius:4px;font-size:0.75rem;font-weight:600;">
                <?= htmlspecialchars($k['kode_kriteria']) ?>
              </span>
              <strong style="margin-left:10px;"><?= htmlspecialchars($k['nama_kriteria']) ?></strong>
              <span style="color:var(--text-muted);font-size:0.85rem;margin-left:8px;">
                — <?= htmlspecialchars($k['pertanyaan']) ?>
              </span>
            </div>
            <button onclick="konfirmasiHapus('<?= BASE_URL ?>/admin/kriteria/hapus/<?= $k['id'] ?>','<?= htmlspecialchars($k['nama_kriteria']) ?>')"
                    class="btn btn-sm btn-danger">Hapus</button>
          </div>

          <!-- Sub kriteria -->
          <?php if (!empty($k['sub_kriteria'])): ?>
            <div style="margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
              <?php foreach ($k['sub_kriteria'] as $sub): ?>
                <span style="background:var(--cream-dark);border:1px solid var(--border);padding:4px 10px;border-radius:6px;font-size:0.82rem;display:flex;align-items:center;gap:6px;">
                  <?= htmlspecialchars($sub['kode_sub']) ?>: <?= htmlspecialchars($sub['nama_sub']) ?>
                  <button onclick="konfirmasiHapus('<?= BASE_URL ?>/admin/kriteria/hapus-sub/<?= $sub['id'] ?>','<?= htmlspecialchars($sub['nama_sub']) ?>')"
                          style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:0.85rem;padding:0;">✕</button>
                </span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p style="color:var(--text-muted);font-size:0.82rem;margin-top:6px;">Belum ada sub kriteria.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>