<?php Session::requireAdmin(); ?>
<!DOCTYPE html><html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Tambah Paket — Admin SPK Umrah</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>
<div class="admin-wrap">
  <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>
  <div class="admin-content">
    <div class="page-title">Tambah Paket Umrah</div>
    <div class="page-subtitle"><a href="<?= BASE_URL ?>/admin/paket">← Kembali ke daftar</a></div>

    <div class="card" style="max-width:700px;">
      <div class="card-header"><h3>Form Paket Baru</h3></div>
      <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/admin/paket/simpan" enctype="multipart/form-data">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
              <label>Kode Paket *</label>
              <input type="text" name="kode_paket" class="form-control" placeholder="PKT009" required>
            </div>
            <div class="form-group">
              <label>Kategori *</label>
              <select name="kategori" class="form-control" required>
                <option value="">Pilih...</option>
                <option value="reguler">Reguler</option>
                <option value="plus">Plus</option>
                <option value="vip">VIP</option>
                <option value="furoda">Furoda</option>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Nama Paket *</label>
              <input type="text" name="nama_paket" class="form-control" placeholder="Paket Umrah Reguler 9 Hari" required>
            </div>
            <div class="form-group">
              <label>Harga (Rp) *</label>
              <input type="text" name="harga" class="form-control" placeholder="22.500.000"
                     oninput="formatRupiah(this)" required>
            </div>
            <div class="form-group">
              <label>Durasi (hari) *</label>
              <input type="number" name="durasi_hari" class="form-control" min="1" placeholder="9" required>
            </div>
            <div class="form-group">
              <label>Maskapai</label>
              <input type="text" name="maskapai" class="form-control" placeholder="Garuda Indonesia">
            </div>
            <div class="form-group">
              <label>Bintang Hotel</label>
              <select name="bintang_hotel" class="form-control">
                <option value="3">★★★ 3 Bintang</option>
                <option value="4">★★★★ 4 Bintang</option>
                <option value="5">★★★★★ 5 Bintang</option>
              </select>
            </div>
            <div class="form-group">
              <label>Hotel Mekkah</label>
              <input type="text" name="hotel_mekkah" class="form-control" placeholder="Nama hotel">
            </div>
            <div class="form-group">
              <label>Hotel Madinah</label>
              <input type="text" name="hotel_madinah" class="form-control" placeholder="Nama hotel">
            </div>
            <div class="form-group">
              <label>Kuota</label>
              <input type="number" name="kuota" class="form-control" min="0" value="0">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Non-aktif</option>
              </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Fasilitas (pisahkan dengan koma)</label>
              <textarea name="fasilitas" class="form-control" rows="2"
                placeholder="Tiket PP, Visa, Muthawif, Hotel Bintang 4, Tur Ziarah"></textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Deskripsi</label>
              <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi lengkap paket..."></textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
              <label>Gambar Paket</label>
              <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
          </div>
          <div class="d-flex gap-2 mt-2">
            <button type="submit" class="btn btn-green">Simpan Paket</button>
            <a href="<?= BASE_URL ?>/admin/paket" class="btn" style="border:1.5px solid var(--border);color:var(--green);">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>
<script src="<?= BASE_URL ?>/assets/js/konsultasi.js"></script>