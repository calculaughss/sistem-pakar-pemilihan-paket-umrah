<?php
$pageTitle = 'Paket Umrah — Persada Al-Amin';
include BASE_PATH . '/app/views/layouts/header.php';

// Kelompokkan paket berdasarkan kategori
$paketByKategori = [];
foreach ($paketList as $p) {
    $paketByKategori[$p['kategori']][] = $p;
}
$urutanKategori = ['reguler', 'plus', 'vip', 'furoda'];
$labelKategori  = [
    'reguler' => '🌙 Reguler',
    'plus'    => '⭐ Plus',
    'vip'     => '👑 VIP',
    'furoda'  => '🌟 Furoda',
];
?>

<!-- Page Header -->
<div style="background: linear-gradient(135deg, var(--green) 0%, var(--green-mid) 100%); padding: 48px 0 40px; text-align:center;">
    <div class="container">
        <div style="font-size:1.8rem; margin-bottom:8px;">🕌</div>
        <h1 style="font-family:var(--ff-serif); color:var(--white); font-size:clamp(1.6rem,3vw,2.4rem); margin-bottom:10px;">
            Paket Umrah Tersedia
        </h1>
        <p style="color:rgba(255,255,255,0.8); max-width:500px; margin:0 auto 20px;">
            Pilih paket yang sesuai kebutuhan, atau biarkan sistem SPK merekomendasikan paket terbaik untuk Anda.
        </p>
        <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-primary">✦ Konsultasi SPK Gratis</a>
    </div>
</div>

<!-- Filter Kategori -->
<div style="background:var(--white); border-bottom:1px solid var(--border); padding:0;">
    <div class="container">
        <div style="display:flex; gap:0; overflow-x:auto;">
            <a href="#semua" style="padding:14px 20px; font-size:0.88rem; font-weight:600; color:var(--green); border-bottom:2px solid var(--green); white-space:nowrap; text-decoration:none;">
                Semua (<?= count($paketList) ?>)
            </a>
            <?php foreach ($urutanKategori as $kat): ?>
                <?php if (!empty($paketByKategori[$kat])): ?>
                    <a href="#<?= $kat ?>" style="padding:14px 20px; font-size:0.88rem; font-weight:500; color:var(--text-muted); border-bottom:2px solid transparent; white-space:nowrap; text-decoration:none; transition:all .2s;"
                       onmouseover="this.style.color='var(--green)'"
                       onmouseout="this.style.color='var(--text-muted)'">
                        <?= $labelKategori[$kat] ?> (<?= count($paketByKategori[$kat]) ?>)
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Daftar Paket Per Kategori -->
<div id="semua" class="section" style="padding-top:40px;">
    <div class="container">
        <?php foreach ($urutanKategori as $kat): ?>
            <?php if (empty($paketByKategori[$kat])) continue; ?>
            <div id="<?= $kat ?>" style="margin-bottom:48px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                    <h2 style="font-family:var(--ff-serif); color:var(--green); font-size:1.4rem;">
                        <?= $labelKategori[$kat] ?>
                    </h2>
                    <span style="height:1px; flex:1; background:var(--border);"></span>
                </div>

                <div class="paket-grid">
                    <?php foreach ($paketByKategori[$kat] as $p): ?>
                        <?php $bintang = str_repeat('★', $p['bintang_hotel']) . str_repeat('☆', 5 - $p['bintang_hotel']); ?>
                        <div class="paket-card">
                            <div class="paket-card-img">
                                <?php if ($p['gambar']): ?>
                                    <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($p['gambar']) ?>"
                                         alt="<?= htmlspecialchars($p['nama_paket']) ?>">
                                <?php else: ?>🕌<?php endif; ?>
                            </div>
                            <div class="paket-card-body">
                                <span class="paket-badge badge-<?= $p['kategori'] ?>"><?= strtoupper($p['kategori']) ?></span>
                                <h3><?= htmlspecialchars($p['nama_paket']) ?></h3>
                                <div class="paket-meta">
                                    <span>✈ <?= htmlspecialchars($p['maskapai']) ?></span>
                                    <span>📅 <?= $p['durasi_hari'] ?> hari</span><br>
                                    <span class="stars"><?= $bintang ?></span>
                                    <span style="font-size:0.78rem; color:var(--text-muted);"> <?= $p['bintang_hotel'] ?> bintang</span>
                                </div>
                                <!-- Highlight 2 fasilitas pertama -->
                                <?php
                                $fasArr = array_slice(explode(',', $p['fasilitas']), 0, 2);
                                foreach ($fasArr as $f): ?>
                                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:2px;">
                                        ✓ <?= htmlspecialchars(trim($f)) ?>
                                    </div>
                                <?php endforeach; ?>
                                <div class="paket-harga" style="margin-top:10px;">
                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                </div>
                            </div>
                            <div class="card-footer" style="justify-content:space-between;">
                                <a href="<?= BASE_URL ?>/paket/detail/<?= $p['id'] ?>"
                                   class="btn btn-sm" style="color:var(--green); border:1.5px solid var(--border);">
                                    Lihat Detail
                                </a>
                                <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-sm btn-green">
                                    Konsultasi
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($paketList)): ?>
            <div style="text-align:center; padding:64px; color:var(--text-muted);">
                <div style="font-size:3rem; margin-bottom:12px;">📦</div>
                <h3 style="font-family:var(--ff-serif); color:var(--text);">Belum ada paket tersedia</h3>
                <p>Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- CTA Banner -->
<section style="background:var(--cream-dark); padding:48px 0; text-align:center; border-top:1px solid var(--border);">
    <div class="container">
        <h2 style="font-family:var(--ff-serif); color:var(--green); font-size:1.5rem; margin-bottom:10px;">
            Masih Bingung Memilih?
        </h2>
        <p style="color:var(--text-muted); margin-bottom:20px; max-width:480px; margin-left:auto; margin-right:auto;">
            Gunakan Sistem Pendukung Keputusan kami. Jawab beberapa pertanyaan dan dapatkan rekomendasi paket terbaik secara otomatis.
        </p>
        <a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn btn-green" style="font-size:1rem; padding:13px 28px;">
            ✦ Mulai Konsultasi Sekarang
        </a>
    </div>
</section>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>
