<?php
$pageTitle = 'Hasil Rekomendasi — SPK Umrah Persada Al-Amin';
include BASE_PATH . '/app/views/layouts/header.php';
?>

<div class="hasil-wrap">

  <!-- Header -->
  <div class="hasil-header">
    <div class="icon-check">✅</div>
    <h2>Rekomendasi Paket Umrah Anda</h2>
    <p style="opacity:0.85;margin:8px 0 0;">
      Halo, <strong><?= htmlspecialchars($konsData['nama_konsultan']) ?></strong> —
      sistem telah menganalisis preferensi Anda menggunakan metode Forward Chaining.
    </p>
    <?php if ($hasil): ?>
      <div class="skor-badge">Kesesuaian: <?= number_format($hasil['skor'], 1) ?>%</div>
    <?php endif; ?>
  </div>

  <?php if ($hasil): ?>
    <!-- Paket Rekomendasi -->
    <div class="hasil-paket">
      <div class="hasil-paket-header">
        <h3>🏆 <?= htmlspecialchars($hasil['nama_paket']) ?></h3>
        <span class="paket-badge badge-<?= $hasil['kategori'] ?>"><?= strtoupper($hasil['kategori']) ?></span>
      </div>
      <div class="hasil-paket-body">
        <div class="detail-grid">
          <div class="detail-item">
            <label>Harga</label>
            <span style="color:var(--gold-dark);font-size:1.2rem;">
              Rp <?= number_format($hasil['harga'], 0, ',', '.') ?>
            </span>
          </div>
          <div class="detail-item">
            <label>Durasi</label>
            <span><?= $hasil['durasi_hari'] ?> Hari</span>
          </div>
          <div class="detail-item">
            <label>Maskapai</label>
            <span><?= htmlspecialchars($hasil['maskapai']) ?></span>
          </div>
          <div class="detail-item">
            <label>Hotel Mekkah</label>
            <span><?= htmlspecialchars($hasil['hotel_mekkah']) ?></span>
          </div>
          <div class="detail-item">
            <label>Hotel Madinah</label>
            <span><?= htmlspecialchars($hasil['hotel_madinah']) ?></span>
          </div>
          <div class="detail-item">
            <label>Bintang Hotel</label>
            <span class="stars"><?= str_repeat('★', $hasil['bintang_hotel']) ?></span>
          </div>
        </div>

        <?php if ($hasil['fasilitas']): ?>
          <div>
            <label style="font-size:0.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;">Fasilitas</label>
            <div class="fasilitas-list mt-1">
              <?php foreach (explode(',', $hasil['fasilitas']) as $f): ?>
                <span class="fasilitas-tag">✓ <?= htmlspecialchars(trim($f)) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="mt-3 d-flex gap-2">
          <a href="<?= BASE_URL ?>/paket/detail/<?= $hasil['paket_id'] ?>" class="btn btn-green">
            Lihat Detail Paket
          </a>
          <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn" style="border:1.5px solid var(--border);color:var(--green);">
            Ulangi Konsultasi
          </a>
        </div>
      </div>
    </div>

    <!-- Jejak Aturan Forward Chaining -->
    <div class="card mt-3">
      <div class="card-header">
        <h3>Jejak Inferensi Forward Chaining</h3>
      </div>
      <div class="card-body">
        <p style="font-size:0.88rem;color:var(--text-muted);margin-bottom:16px;">
          Aturan-aturan berikut terpicu berdasarkan jawaban Anda:
        </p>
        <?php if (!empty($aturanTerpicu)): ?>
          <?php foreach ($aturanTerpicu as $kode): ?>
            <div class="aturan-box">
              <div class="kode">⚡ <?= htmlspecialchars($kode) ?></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--text-muted);font-size:0.88rem;">Tidak ada aturan eksplisit — sistem menggunakan rekomendasi default.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Jawaban User -->
    <div class="card mt-3">
      <div class="card-header">
        <h3>Ringkasan Jawaban Anda</h3>
      </div>
      <div class="card-body" style="padding:0;">
        <table>
          <thead><tr><th>Kriteria</th><th>Jawaban</th></tr></thead>
          <tbody>
            <?php foreach ($jawaban as $j): ?>
              <tr>
                <td><?= htmlspecialchars($j['nama_kriteria']) ?></td>
                <td><strong><?= htmlspecialchars($j['nama_sub']) ?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  <?php else: ?>
    <div class="alert alert-danger">
      ⚠ Tidak ada rekomendasi yang dapat diberikan. Silakan <a href="<?= BASE_URL ?>/konsultasi/mulai">ulangi konsultasi</a>.
    </div>
  <?php endif; ?>

  <div class="text-center mt-3">
    <a href="<?= BASE_URL ?>/" class="btn btn-sm" style="color:var(--text-muted);">← Kembali ke Beranda</a>
  </div>
</div>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>