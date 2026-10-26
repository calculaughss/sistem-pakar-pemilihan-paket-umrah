<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Tambah Aturan — Admin SPK Umrah</title>
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
    <div class="page-title">Tambah Aturan Baru</div>
    <div class="page-subtitle"><a href="<?= BASE_URL ?>/admin/aturan">← Kembali ke daftar aturan</a></div>

    <div class="card" style="max-width:760px;">
      <div class="card-header"><h3>Form Aturan Forward Chaining (IF–THEN)</h3></div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/admin/aturan/simpan">

          <div style="background:var(--cream);border-left:4px solid var(--gold);padding:12px 16px;border-radius:0 6px 6px 0;margin-bottom:20px;font-size:0.88rem;color:var(--text-muted);">
            ℹ Format aturan: <strong>IF</strong> [kondisi 1] <strong>AND</strong> [kondisi 2] … <strong>THEN</strong> [rekomendasikan paket]
          </div>

          <!-- THEN: Paket -->
          <div class="form-group">
            <label>THEN — Rekomendasikan Paket *</label>
            <select name="paket_id" class="form-control" required>
              <option value="">Pilih paket...</option>
              <?php foreach ($paketList as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['kode_paket'] . ' — ' . $p['nama_paket']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Bobot Aturan (0.1 – 1.0)</label>
            <input type="number" name="bobot" class="form-control" step="0.1" min="0.1" max="1.0" value="1.0">
          </div>

          <div class="form-group">
            <label>Deskripsi Aturan (opsional)</label>
            <input type="text" name="deskripsi" class="form-control" placeholder="Penjelasan singkat aturan ini">
          </div>

          <!-- IF: Kondisi per Kriteria -->
          <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">
          <label style="font-weight:700;font-size:0.9rem;color:var(--green);">
            IF — Pilih Kondisi (minimal 1 kriteria)
          </label>
          <p style="font-size:0.82rem;color:var(--text-muted);margin:4px 0 16px;">
            Pilih kriteria lalu centang sub kriteria yang menjadi kondisi. Semua kondisi yang dipilih digabung dengan AND.
          </p>

          <?php foreach ($kriteriaList as $k): ?>
            <div style="border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:12px;overflow:hidden;">
              <div style="background:var(--cream);padding:10px 14px;font-weight:600;font-size:0.88rem;color:var(--green);border-bottom:1px solid var(--border);">
                <?= htmlspecialchars($k['kode_kriteria']) ?> — <?= htmlspecialchars($k['nama_kriteria']) ?>
              </div>
              <div style="padding:12px 14px;display:flex;flex-wrap:wrap;gap:8px;">
                <?php foreach ($k['sub_kriteria'] as $sub): ?>
                  <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:5px 10px;border:1px solid var(--border);border-radius:6px;font-size:0.85rem;background:var(--white);">
                    <input type="checkbox" name="sub_kriteria_ids[]" value="<?= $sub['id'] ?>">
                    <?= htmlspecialchars($sub['nama_sub']) ?>
                    <small style="color:var(--text-muted);">(<?= $sub['kode_sub'] ?>)</small>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-green">Simpan Aturan</button>
            <a href="<?= BASE_URL ?>/admin/aturan" class="btn" style="border:1.5px solid var(--border);color:var(--green);">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>