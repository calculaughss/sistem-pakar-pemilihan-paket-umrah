<?php
$pageTitle = 'Konsultasi SPK — Persada Al-Amin';
include BASE_PATH . '/app/views/layouts/header.php';
?>

<div class="konsultasi-wrap">
  <div style="text-align:center;margin-bottom:36px;">
    <div style="font-size:2.5rem;margin-bottom:10px;">🕌</div>
    <h1 style="font-family:var(--ff-serif);color:var(--green);font-size:1.9rem;">Konsultasi Pemilihan Paket Umrah</h1>
    <p style="color:var(--text-muted);margin-top:8px;">Isi data Anda untuk memulai sesi konsultasi dengan sistem Forward Chaining.</p>
  </div>

  <?php if ($msg = Session::getFlash('error')): ?>
    <div class="alert alert-danger" data-dismiss>⚠ <?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="pertanyaan-card">
    <form method="POST" action="<?= BASE_URL ?>/konsultasi/mulai">
      <div class="form-group">
        <label>Nama Lengkap <span style="color:var(--danger)">*</span></label>
        <input type="text" name="nama" class="form-control"
               placeholder="Masukkan nama lengkap Anda"
               value="<?= htmlspecialchars(Session::get('nama', '')) ?>" required>
      </div>
      <div class="form-group">
        <label>No. HP / WhatsApp</label>
        <input type="text" name="no_hp" class="form-control"
               placeholder="08xx-xxxx-xxxx (opsional)">
      </div>

      <div style="background:var(--cream);border-radius:var(--radius-sm);padding:14px 16px;margin:16px 0;font-size:0.87rem;color:var(--text-muted);">
        ℹ Sistem akan mengajukan beberapa pertanyaan seputar preferensi Anda,
        kemudian memberikan rekomendasi paket umrah yang paling sesuai menggunakan
        metode <strong>Forward Chaining</strong>.
      </div>

      <button type="submit" class="btn btn-green" style="width:100%;justify-content:center;font-size:1rem;padding:13px;">
        ✦ Mulai Konsultasi
      </button>
    </form>
  </div>

  <p class="text-center mt-2" style="font-size:0.85rem;color:var(--text-muted);">
    <a href="<?= BASE_URL ?>/">← Kembali ke Beranda</a>
  </p>
</div>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>