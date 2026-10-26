<?php
$pageTitle = htmlspecialchars($data['nama_paket']) . ' — SPK Umrah';
include BASE_PATH . '/app/views/layouts/header.php';
$bintang = str_repeat('★', $data['bintang_hotel']) . str_repeat('☆', 5 - $data['bintang_hotel']);
?>

<div style="max-width:860px;margin:0 auto;padding:48px 24px;">
  <a href="<?= BASE_URL ?>/" style="font-size:0.88rem;color:var(--text-muted);">← Kembali</a>

  <div class="card mt-2">
    <div style="height:220px;background:var(--green-mid);display:flex;align-items:center;justify-content:center;font-size:5rem;">
      <?php if ($data['gambar']): ?>
        <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($data['gambar']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
      <?php else: ?>🕌<?php endif; ?>
    </div>
    <div class="card-body">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;">
        <div>
          <span class="paket-badge badge-<?= $data['kategori'] ?>"><?= strtoupper($data['kategori']) ?></span>
          <h1 style="font-family:var(--ff-serif);color:var(--green);font-size:1.7rem;margin-top:6px;">
            <?= htmlspecialchars($data['nama_paket']) ?>
          </h1>
          <div class="stars" style="font-size:1rem;"><?= $bintang ?></div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:0.78rem;color:var(--text-muted);">Mulai dari</div>
          <div style="font-family:var(--ff-serif);font-size:2rem;color:var(--gold-dark);font-weight:700;">
            Rp <?= number_format($data['harga'], 0, ',', '.') ?>
          </div>
          <div style="font-size:0.82rem;color:var(--text-muted);">/orang</div>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">

      <div class="detail-grid">
        <div class="detail-item"><label>Kode Paket</label><span><?= htmlspecialchars($data['kode_paket']) ?></span></div>
        <div class="detail-item"><label>Durasi</label><span><?= $data['durasi_hari'] ?> Hari</span></div>
        <div class="detail-item"><label>Maskapai</label><span><?= htmlspecialchars($data['maskapai']) ?></span></div>
        <div class="detail-item"><label>Kuota</label><span><?= $data['kuota'] ?> Orang</span></div>
        <div class="detail-item"><label>Hotel Mekkah</label><span><?= htmlspecialchars($data['hotel_mekkah']) ?></span></div>
        <div class="detail-item"><label>Hotel Madinah</label><span><?= htmlspecialchars($data['hotel_madinah']) ?></span></div>
      </div>

      <?php if ($data['fasilitas']): ?>
        <h4 style="font-family:var(--ff-serif);color:var(--green);margin:20px 0 10px;">Fasilitas Termasuk</h4>
        <div class="fasilitas-list">
          <?php foreach (explode(',', $data['fasilitas']) as $f): ?>
            <span class="fasilitas-tag">✓ <?= htmlspecialchars(trim($f)) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($data['deskripsi']): ?>
        <h4 style="font-family:var(--ff-serif);color:var(--green);margin:20px 0 8px;">Deskripsi</h4>
        <p style="color:var(--text-muted);line-height:1.7;"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
      <?php endif; ?>

      <div class="mt-3 d-flex gap-2">
        <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-primary">✦ Konsultasi Sekarang</a>
        <a href="<?= BASE_URL ?>/paket" class="btn" style="border:1.5px solid var(--border);color:var(--green);">Paket Lain</a>
      </div>
    </div>
  </div>
</div>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>