<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Edit Paket — Admin SPK Umrah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Edit Paket Umrah</div>
    <div class="page-subtitle"><a href="<?= BASE_URL ?>/admin/paket">← Kembali ke daftar</a></div>

    <div class="card" style="max-width:700px;">
      <div class="card-header"><h3>Edit: <?= htmlspecialchars($data['nama_paket']) ?></h3></div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/admin/paket/update">
          <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group" style="grid-column:1/-1;">
              <label>Nama Paket *</label>
              <input type="text" name="nama_paket" class="form-control" value="<?= htmlspecialchars($data['nama_paket']) ?>" required>
            </div>
            <div class="form-group">
              <label>Kategori *</label>
              <select name="kategori" class="form-control">
                <?php foreach (['reguler','plus','vip','furoda'] as $kat): ?>
                  <option value="<?= $kat ?>" <?= $data['kategori']==$kat?'selected':'' ?>><?= ucfirst($kat) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Harga (Rp) *</label>
              <input type="text" name="harga" class="form-control"
                     value="<?= number_format($data['harga'],0,',','.') ?>"
                     oninput="formatRupiah(this)" required>
            </div>
            <div class="form-group">
              <label>Durasi (hari) *</label>
              <input type="number" name="durasi_hari" class="form-control" value="<?= $data['durasi_hari'] ?>" required>
            </div>
            <div class="form-group">
              <label>Maskapai</label>
              <input type="text" name="maskapai" class="form-control" value="<?= htmlspecialchars($data['maskapai']) ?>">
            </div>
            <div class="form-group">
              <label>Bintang Hotel</label>
              <select name="bintang_hotel" class="form-control">
                <?php for ($b=3;$b<=5;$b++): ?>
                  <option value="<?= $b ?>" <?= $data['bintang_hotel']==$b?'selected':'' ?>><?= str_repeat('★',$b) ?> <?= $b ?> Bintang</option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Hotel Mekkah</label>
              <input type="text" name="hotel_mekkah" class="form-control" value="<?= htmlspecialchars($data['hotel_mekkah']) ?>">
            </div>
            <div class="form-group">
              <label>Hotel Madinah</label>
              <input type="text" name="hotel_madinah" class="form-control" value="<?= htmlspecialchars($data['hotel_madinah']) ?>">
            </div>
            <div class="form-group">
              <label>Kuota</label>
              <input type="number" name="kuota" class="form-control" value="<?= $data['kuota'] ?>">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="aktif" <?= $data['status']=='aktif'?'selected':'' ?>>Aktif</option>
                <option value="nonaktif" <?= $data['status']=='nonaktif'?'selected':'' ?>>Non-aktif</option>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Fasilitas</label>
              <textarea name="fasilitas" class="form-control" rows="2"><?= htmlspecialchars($data['fasilitas']) ?></textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>
          </div>
          <div class="d-flex gap-2 mt-2">
            <button type="submit" class="btn btn-green">Simpan Perubahan</button>
            <a href="<?= BASE_URL ?>/admin/paket" class="btn" style="border:1.5px solid var(--border);color:var(--green);">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>
<script src="<?= BASE_URL ?>/assets/js/konsultasi.js"></script>