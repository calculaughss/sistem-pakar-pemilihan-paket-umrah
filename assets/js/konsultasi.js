/* konsultasi.js — SPK Umrah Persada Al-Amin */

const BASE_URL = document.querySelector('meta[name="base-url"]')?.content ?? '';

/* ── Konsultasi AJAX Stepper ─────────────────────────────── */
class KonsultasiStepper {
  constructor(containerId) {
    this.container   = document.getElementById(containerId);
    this.step        = 0;
    this.total       = 0;
    this.jawabanMap  = {};   // { kriteria_id: sub_kriteria_id }
    if (this.container) this.init();
  }

  async init() {
    await this.loadPertanyaan(0);
  }

  async loadPertanyaan(step) {
    this.showLoading();
    try {
      const res  = await fetch(`${BASE_URL}/api/pertanyaan?step=${step}`);
      const data = await res.json();

      if (data.selesai) {
        await this.prosesInferensi();
        return;
      }

      this.step  = data.step;
      this.total = data.total;
      this.renderPertanyaan(data.kriteria);
      this.updateProgress();
    } catch (e) {
      this.showError('Gagal memuat pertanyaan. Silakan coba lagi.');
    }
  }

  renderPertanyaan(kriteria) {
    const html = `
      <div class="pertanyaan-card" data-kriteria="${kriteria.id}">
        <div class="pertanyaan-num">Pertanyaan ${this.step + 1} dari ${this.total}</div>
        <div class="pertanyaan-teks">${kriteria.pertanyaan}</div>
        <div class="pilihan-list">
          ${kriteria.sub_kriteria.map(sub => `
            <div class="pilihan-item" data-sub="${sub.id}" onclick="stepper.pilih(this, ${kriteria.id}, ${sub.id})">
              <input type="radio" name="jawaban" value="${sub.id}" id="sub_${sub.id}">
              <label for="sub_${sub.id}">${sub.nama_sub}</label>
            </div>
          `).join('')}
        </div>
        <div class="mt-3 d-flex gap-2" style="justify-content: flex-end;">
          ${this.step > 0 ? `<button class="btn btn-outline" style="color:var(--green);border-color:var(--border);" onclick="stepper.kembali()">← Kembali</button>` : ''}
          <button class="btn btn-green" id="btn-lanjut" onclick="stepper.lanjut()" disabled>
            ${this.step + 1 === this.total ? 'Lihat Rekomendasi →' : 'Lanjut →'}
          </button>
        </div>
      </div>`;
    this.container.innerHTML = html;

    // Restore jawaban sebelumnya jika ada
    if (this.jawabanMap[this.step]) {
      const prevSub = this.jawabanMap[this.step].sub_id;
      const prevEl  = this.container.querySelector(`[data-sub="${prevSub}"]`);
      if (prevEl) {
        prevEl.classList.add('selected');
        prevEl.querySelector('input').checked = true;
        document.getElementById('btn-lanjut').disabled = false;
      }
    }
  }

  pilih(el, kriteriaId, subId) {
    this.container.querySelectorAll('.pilihan-item').forEach(p => p.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
    document.getElementById('btn-lanjut').disabled = false;
    this.selectedKriteria = kriteriaId;
    this.selectedSub      = subId;
  }

  async lanjut() {
    if (!this.selectedSub) return;

    // Simpan jawaban ke server
    await fetch(`${BASE_URL}/api/simpan-jawab`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        kriteria_id:    this.selectedKriteria,
        sub_kriteria_id: this.selectedSub,
      }),
    });

    this.jawabanMap[this.step] = { kriteria_id: this.selectedKriteria, sub_id: this.selectedSub };
    this.selectedSub = null;
    await this.loadPertanyaan(this.step + 1);
  }

  kembali() {
    if (this.step > 0) this.loadPertanyaan(this.step - 1);
  }

  async prosesInferensi() {
    this.container.innerHTML = `
      <div class="pertanyaan-card text-center" style="padding:60px 40px;">
        <div style="font-size:3rem;margin-bottom:16px;">⚙️</div>
        <h3 style="font-family:var(--ff-serif);color:var(--green);margin-bottom:8px;">
          Memproses Rekomendasi…
        </h3>
        <p style="color:var(--text-muted);">Sistem sedang menganalisis jawaban Anda menggunakan metode Forward Chaining.</p>
        <div class="progress-bar-wrap mt-3"><div class="progress-bar-fill" style="width:100%;animation:pulse 1s infinite;"></div></div>
      </div>`;

    try {
      const res  = await fetch(`${BASE_URL}/api/inferensi`);
      const data = await res.json();
      if (data.redirect) window.location.href = data.redirect;
    } catch {
      this.showError('Gagal memproses inferensi.');
    }
  }

  updateProgress() {
    const pct = Math.round((this.step / this.total) * 100);
    const bar = document.getElementById('progress-fill');
    const lbl = document.getElementById('progress-label');
    if (bar) bar.style.width = pct + '%';
    if (lbl) lbl.textContent = `${this.step} / ${this.total} dijawab`;
  }

  showLoading() {
    this.container.innerHTML = `
      <div class="pertanyaan-card text-center" style="padding:48px;">
        <div style="font-size:2rem;margin-bottom:12px;">🔄</div>
        <p style="color:var(--text-muted);">Memuat pertanyaan…</p>
      </div>`;
  }

  showError(msg) {
    this.container.innerHTML = `<div class="alert alert-danger">⚠ ${msg}</div>`;
  }
}

/* ── Admin Utilities ─────────────────────────────────────── */

// Konfirmasi hapus
function konfirmasiHapus(url, nama) {
  if (confirm(`Yakin ingin menghapus "${nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
    window.location.href = url;
  }
}

// Format angka jadi rupiah saat mengetik
function formatRupiah(input) {
  let val = input.value.replace(/\D/g, '');
  input.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Load sub kriteria berdasarkan kriteria_id (untuk form tambah aturan)
async function loadSubKriteria(kriteriaId, targetId) {
  if (!kriteriaId) return;
  try {
    const res  = await fetch(`${BASE_URL}/api/sub-kriteria/${kriteriaId}`);
    const data = await res.json();
    const wrap = document.getElementById(targetId);
    if (!wrap) return;
    wrap.innerHTML = data.map(sub => `
      <label class="pilihan-item" style="cursor:pointer;">
        <input type="checkbox" name="sub_kriteria_ids[]" value="${sub.id}">
        <span>${sub.nama_sub} <small style="color:var(--text-muted)">(${sub.kode_sub})</small></span>
      </label>
    `).join('');
  } catch {
    console.error('Gagal memuat sub kriteria');
  }
}

// Flash message auto-dismiss
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.alert[data-dismiss]').forEach(el => {
    setTimeout(() => el.style.display = 'none', 4000);
  });
});