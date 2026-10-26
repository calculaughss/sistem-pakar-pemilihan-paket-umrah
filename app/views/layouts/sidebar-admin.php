<aside class="sidebar">
  <div style="padding:0 20px 16px;border-bottom:1px solid rgba(255,255,255,0.1);margin-bottom:12px;">
    <div style="font-family:var(--ff-serif);color:var(--gold-light);font-size:1rem;">Admin Panel</div>
    <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);">SPK Umrah Persada Al-Amin</div>
  </div>

  <div class="sidebar-title">Menu Utama</div>
  <a href="<?= BASE_URL ?>/admin/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'],'dashboard') !== false ? 'active' : '' ?>">
    <span class="icon">📊</span> Dashboard
  </a>

  <div class="sidebar-title" style="margin-top:12px;">Data Master</div>
  <a href="<?= BASE_URL ?>/admin/paket" class="<?= strpos($_SERVER['REQUEST_URI'],'/admin/paket') !== false ? 'active' : '' ?>">
    <span class="icon">📦</span> Paket Umrah
  </a>
  <a href="<?= BASE_URL ?>/admin/kriteria" class="<?= strpos($_SERVER['REQUEST_URI'],'kriteria') !== false ? 'active' : '' ?>">
    <span class="icon">❓</span> Kriteria & Sub
  </a>
  <a href="<?= BASE_URL ?>/admin/aturan" class="<?= strpos($_SERVER['REQUEST_URI'],'aturan') !== false ? 'active' : '' ?>">
    <span class="icon">⚡</span> Basis Aturan
  </a>

  <div class="sidebar-title" style="margin-top:12px;">Laporan</div>
  <a href="<?= BASE_URL ?>/admin/laporan" class="<?= strpos($_SERVER['REQUEST_URI'],'laporan') !== false ? 'active' : '' ?>">
    <span class="icon">📋</span> Riwayat Konsultasi
  </a>

  <div style="margin-top:auto;padding:16px 20px 0;border-top:1px solid rgba(255,255,255,0.1);margin-top:24px;">
    <a href="<?= BASE_URL ?>/" style="font-size:0.82rem;color:rgba(255,255,255,0.5);">← Ke Situs</a><br>
    <a href="<?= BASE_URL ?>/logout" style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Logout</a>
  </div>
</aside>