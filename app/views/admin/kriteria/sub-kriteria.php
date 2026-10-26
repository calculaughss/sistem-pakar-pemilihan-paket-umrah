<?php Session::requireAdmin(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub Kriteria — Admin SPK Umrah</title>
    <meta name="base-url" content="<?= BASE_URL ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<?php include BASE_PATH . '/app/views/layouts/header.php'; ?>

<div class="admin-wrap">
    <?php include BASE_PATH . '/app/views/layouts/sidebar-admin.php'; ?>

    <div class="admin-content">
        <div class="page-title">Sub Kriteria</div>
        <div class="page-subtitle">
            <a href="<?= BASE_URL ?>/admin/kriteria">← Kembali ke Kriteria</a>
        </div>

        <?php if ($msg = Session::getFlash('success')): ?>
            <div class="alert alert-success" data-dismiss>✓ <?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Session::getFlash('error')): ?>
            <div class="alert alert-danger" data-dismiss>⚠ <?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: 1fr 1.4fr; gap:24px; align-items:start;">

            <!-- Form Tambah Sub Kriteria -->
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Sub Kriteria</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/admin/kriteria/simpan" id="form-sub">
                        <input type="hidden" name="tipe" value="sub">

                        <div class="form-group">
                            <label>Kriteria Induk <span style="color:var(--danger)">*</span></label>
                            <select name="kriteria_id" id="sel-kriteria" class="form-control" required
                                    onchange="previewKode(this)">
                                <option value="">— Pilih Kriteria —</option>
                                <?php foreach ($kriteriaList as $k): ?>
                                    <option value="<?= $k['id'] ?>"
                                            data-kode="<?= htmlspecialchars($k['kode_kriteria']) ?>"
                                            data-total="<?= count($k['sub_kriteria']) ?>">
                                        <?= htmlspecialchars($k['kode_kriteria']) ?> — <?= htmlspecialchars($k['nama_kriteria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kode Sub <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="kode_sub" id="inp-kode-sub" class="form-control"
                                   placeholder="K01_E" required
                                   style="font-family:monospace; letter-spacing:.04em;">
                            <small style="color:var(--text-muted); font-size:0.78rem; display:block; margin-top:4px;">
                                Format: KXX_A, KXX_B, … (huruf kapital)
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Nama Sub Kriteria <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="nama_sub" class="form-control"
                                   placeholder="Contoh: Rp 60 juta – Rp 80 juta" required>
                        </div>

                        <div class="form-group">
                            <label>Keterangan <span style="color:var(--text-muted)">(opsional)</span></label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Penjelasan tambahan..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-green" style="width:100%; justify-content:center;">
                            + Simpan Sub Kriteria
                        </button>
                    </form>
                </div>
            </div>

            <!-- Daftar Sub Kriteria per Kriteria -->
            <div>
                <?php foreach ($kriteriaList as $k): ?>
                    <div class="cb-kriteria-block" style="margin-bottom:14px;">
                        <div class="cb-kriteria-header">
                            <span style="background:var(--green); color:var(--white); padding:2px 8px;
                                  border-radius:4px; font-size:0.75rem; font-weight:700; font-family:monospace;">
                                <?= htmlspecialchars($k['kode_kriteria']) ?>
                            </span>
                            <span><?= htmlspecialchars($k['nama_kriteria']) ?></span>
                            <span style="margin-left:auto; font-size:0.78rem; color:var(--text-muted); font-weight:400;">
                                <?= count($k['sub_kriteria']) ?> sub
                            </span>
                        </div>

                        <?php if (!empty($k['sub_kriteria'])): ?>
                            <div style="padding:12px 14px;">
                                <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                                    <thead>
                                        <tr style="background:var(--cream);">
                                            <th style="padding:7px 10px; text-align:left; font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.04em;">Kode</th>
                                            <th style="padding:7px 10px; text-align:left; font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.04em;">Nama Sub</th>
                                            <th style="padding:7px 10px; text-align:left; font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.04em;">Keterangan</th>
                                            <th style="padding:7px 10px; width:60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($k['sub_kriteria'] as $sub): ?>
                                            <tr style="border-top:1px solid var(--border);">
                                                <td style="padding:8px 10px;">
                                                    <code style="background:var(--cream); padding:2px 6px; border-radius:4px; font-size:0.78rem;">
                                                        <?= htmlspecialchars($sub['kode_sub']) ?>
                                                    </code>
                                                </td>
                                                <td style="padding:8px 10px; font-weight:500;">
                                                    <?= htmlspecialchars($sub['nama_sub']) ?>
                                                </td>
                                                <td style="padding:8px 10px; color:var(--text-muted); font-size:0.82rem;">
                                                    <?= htmlspecialchars($sub['keterangan'] ?? '—') ?>
                                                </td>
                                                <td style="padding:8px 10px;">
                                                    <button onclick="hapusSub(<?= $sub['id'] ?>, '<?= htmlspecialchars($sub['nama_sub'], ENT_QUOTES) ?>')"
                                                            class="btn btn-sm btn-danger" style="padding:4px 8px; font-size:0.78rem;">
                                                        ✕
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div style="padding:16px 14px; color:var(--text-muted); font-size:0.85rem; text-align:center;">
                                Belum ada sub kriteria. Tambahkan menggunakan form di sebelah kiri.
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($kriteriaList)): ?>
                    <div class="empty-state">
                        <div class="icon">❓</div>
                        <h4>Belum ada kriteria</h4>
                        <p>Buat kriteria terlebih dahulu sebelum menambahkan sub kriteria.</p>
                        <a href="<?= BASE_URL ?>/admin/kriteria" class="btn btn-green">Kelola Kriteria</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>

<script>
// Auto-generate kode sub berdasarkan kriteria yang dipilih
function previewKode(sel) {
    const opt    = sel.options[sel.selectedIndex];
    const kode   = opt.dataset.kode ?? '';
    const total  = parseInt(opt.dataset.total ?? 0);
    const huruf  = String.fromCharCode(65 + total); // A=65, B=66, ...
    const inp    = document.getElementById('inp-kode-sub');
    if (kode && inp) inp.value = kode + '_' + huruf;
}

// Hapus sub kriteria dengan konfirmasi
function hapusSub(id, nama) {
    if (confirm(`Hapus sub kriteria "${nama}"?\nPeringatan: sub kriteria yang digunakan di basis aturan juga akan terhapus!`)) {
        window.location.href = `${document.querySelector('meta[name="base-url"]').content}/admin/kriteria/hapus-sub/${id}`;
    }
}
</script>
