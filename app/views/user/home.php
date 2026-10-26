<?php
$pageTitle = 'Beranda — SPK Umrah Persada Al-Amin';
include BASE_PATH . '/app/views/layouts/header.php';
?>

<!-- Hero -->
<section class="hero">
  <div class="container hero-content">
    <div class="hero-kaligrafi">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</div>
    <h1>Temukan Paket Umrah<br><span>Terbaik untuk Anda</span></h1>
    <p>Sistem Pendukung Keputusan berbasis <strong>Forward Chaining</strong> membantu Anda memilih paket umrah yang paling sesuai dengan kebutuhan dan anggaran.</p>
    <div class="hero-actions">
      <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-primary">
        ✦ Mulai Konsultasi SPK
      </a>
      <a href="#paket-list" class="btn btn-outline">Lihat Semua Paket ↓</a>
    </div>
  </div>
</section>

<!-- Cara Kerja -->
<section class="section" style="background:var(--white);padding:48px 0;">
  <div class="container">
    <h2 class="section-title">Bagaimana Sistem Bekerja?</h2>
    <p class="section-subtitle">Metode Forward Chaining menyesuaikan kebutuhan Anda secara otomatis</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;margin-top:16px;">
      <?php
      $steps = [
        ['icon'=>'📋','num'=>'01','judul'=>'Isi Konsultasi','desc'=>'Jawab beberapa pertanyaan tentang preferensi dan anggaran Anda'],
        ['icon'=>'⚙️','num'=>'02','judul'=>'Proses Inferensi','desc'=>'Sistem mencocokkan jawaban dengan basis aturan Forward Chaining'],
        ['icon'=>'✅','num'=>'03','judul'=>'Dapat Rekomendasi','desc'=>'Paket terbaik yang sesuai ditampilkan lengkap dengan alasannya'],
      ];
      foreach ($steps as $s): ?>
        <div style="text-align:center;padding:24px;border-radius:var(--radius);border:1px solid var(--border);">
          <div style="font-size:2.2rem;margin-bottom:8px;"><?= $s['icon'] ?></div>
          <div style="font-size:0.72rem;font-weight:700;color:var(--gold-dark);letter-spacing:0.1em;margin-bottom:6px;"><?= $s['num'] ?></div>
          <h4 style="font-family:var(--ff-serif);color:var(--green);margin-bottom:8px;"><?= $s['judul'] ?></h4>
          <p style="font-size:0.88rem;color:var(--text-muted);"><?= $s['desc'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Daftar Paket -->
<section class="section" id="paket-list">
  <div class="container">
    <h2 class="section-title">Paket Umrah Tersedia</h2>
    <p class="section-subtitle">Pilih paket yang sesuai, atau gunakan konsultasi SPK untuk rekomendasi otomatis</p>

    <div class="paket-grid">
      <?php foreach ($paketList as $p): ?>
        <?php
        $bintang = str_repeat('★', $p['bintang_hotel']) . str_repeat('☆', 5 - $p['bintang_hotel']);
        ?>
        <div class="paket-card">
          <div class="paket-card-img">
            <?php if ($p['gambar']): ?>
              <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama_paket']) ?>">
            <?php else: ?>
              🕌
            <?php endif; ?>
          </div>
          <div class="paket-card-body">
            <span class="paket-badge badge-<?= $p['kategori'] ?>"><?= strtoupper($p['kategori']) ?></span>
            <h3><?= htmlspecialchars($p['nama_paket']) ?></h3>
            <div class="paket-meta">
              <span>✈ <?= htmlspecialchars($p['maskapai']) ?></span>
              <span>📅 <?= $p['durasi_hari'] ?> hari</span><br>
              <span class="stars"><?= $bintang ?></span>
              <span style="font-size:0.78rem;color:var(--text-muted);"> <?= $p['bintang_hotel'] ?> bintang</span>
            </div>
            <div class="paket-harga">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
          </div>
          <div class="card-footer" style="justify-content:space-between;">
            <a href="<?= BASE_URL ?>/paket/detail/<?= $p['id'] ?>" class="btn btn-sm btn-outline" style="color:var(--green);border-color:var(--border);">
              Detail
            </a>
            <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-sm btn-green">Konsultasi</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:var(--green);padding:64px 0;text-align:center;">
  <div class="container">
    <h2 style="font-family:var(--ff-serif);color:var(--gold-light);font-size:clamp(1.5rem,3vw,2rem);margin-bottom:12px;">
      Bingung Memilih? Biarkan Sistem yang Membantu
    </h2>
    <p style="color:rgba(255,255,255,0.8);margin-bottom:28px;max-width:500px;margin-left:auto;margin-right:auto;">
      Jawab beberapa pertanyaan singkat dan dapatkan rekomendasi paket umrah terbaik yang sesuai kebutuhan Anda.
    </p>
    <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-primary" style="font-size:1rem;padding:13px 32px;">
      ✦ Mulai Konsultasi Gratis
    </a>
  </div>
</section>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>