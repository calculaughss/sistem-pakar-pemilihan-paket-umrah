<?php
$pageTitle  = 'Pertanyaan ' . ($step + 1) . ' — SPK Umrah';
$totalPct   = $total > 0 ? round(($step / $total) * 100) : 0;
include BASE_PATH . '/app/views/layouts/header.php';
?>

<div class="konsultasi-wrap">
  <!-- Progress -->
  <div class="step-indicator">
    <span id="progress-label"><?= $step ?> / <?= $total ?> dijawab</span>
    <span style="color:var(--gold-dark);font-weight:600;"><?= $totalPct ?>%</span>
  </div>
  <div class="progress-bar-wrap">
    <div class="progress-bar-fill" id="progress-fill" style="width:<?= $totalPct ?>%"></div>
  </div>

  <!-- AJAX Container -->
  <div id="pertanyaan-container">
    <!-- Render pertanyaan pertama langsung (non-AJAX fallback) -->
    <div class="pertanyaan-card" data-kriteria="<?= $kriteriaSekarang['id'] ?>">
      <div class="pertanyaan-num">Pertanyaan <?= $step + 1 ?> dari <?= $totalKriteria ?></div>
      <div class="pertanyaan-teks"><?= htmlspecialchars($kriteriaSekarang['pertanyaan']) ?></div>

      <form method="POST" action="<?= BASE_URL ?>/konsultasi/jawab" id="form-jawab">
        <input type="hidden" name="kriteria_id" value="<?= $kriteriaSekarang['id'] ?>">
        <div class="pilihan-list" id="pilihan-list">
          <?php foreach ($kriteriaSekarang['sub_kriteria'] as $sub): ?>
            <div class="pilihan-item" onclick="pilihOpsi(this)">
              <input type="radio" name="sub_kriteria_id" value="<?= $sub['id'] ?>" id="sub_<?= $sub['id'] ?>" required>
              <label for="sub_<?= $sub['id'] ?>"><?= htmlspecialchars($sub['nama_sub']) ?></label>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-3 d-flex gap-2" style="justify-content:flex-end;">
          <?php if ($step > 0): ?>
            <a href="javascript:history.back()" class="btn btn-sm" style="color:var(--green);border:1.5px solid var(--border);background:var(--white);">
              ← Kembali
            </a>
          <?php endif; ?>
          <button type="submit" class="btn btn-green" id="btn-lanjut" disabled>
            <?= ($step + 1 === $totalKriteria) ? 'Lihat Rekomendasi →' : 'Lanjut →' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$extraJs = '<script src="' . BASE_URL . '/assets/js/konsultasi.js"></script>';
include BASE_PATH . '/app/views/layouts/footer.php';
?>
<script>
function pilihOpsi(el) {
  document.querySelectorAll('.pilihan-item').forEach(p => p.classList.remove('selected'));
  el.classList.add('selected');
  el.querySelector('input').checked = true;
  document.getElementById('btn-lanjut').disabled = false;
}
</script>