<?php
class AdminController {
    private Paket $paket;
    private Kriteria $kriteria;
    private Aturan $aturan;
    private Konsultasi $konsultasi;

    public function __construct() {
        $this->paket      = new Paket();
        $this->kriteria   = new Kriteria();
        $this->aturan     = new Aturan();
        $this->konsultasi = new Konsultasi();
    }

    public function dashboard(): void {
        $stats = [
            'total_paket'      => $this->paket->count(),
            'total_kriteria'   => $this->kriteria->count(),
            'total_aturan'     => $this->aturan->count(),
            'total_konsultasi' => $this->konsultasi->count(),
            'konsultasi_selesai' => $this->konsultasi->countSelesai(),
        ];
        $konsultasiTerbaru = $this->konsultasi->getAll(5);
        include BASE_PATH . '/app/views/admin/dashboard.php';
    }

    // ── Paket ─────────────────────────────────────────────────────
    public function daftarPaket(): void {
        $paketList = $this->paket->getAll('aktif');
        include BASE_PATH . '/app/views/admin/paket/index.php';
    }

    public function tambahPaket(): void {
        include BASE_PATH . '/app/views/admin/paket/tambah.php';
    }

    public function simpanPaket(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/paket');
            exit;
        }

        $gambar = '';
        if (!empty($_FILES['gambar']['name'])) {
            $ext    = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $nama   = 'paket_' . time() . '.' . $ext;
            $target = BASE_PATH . '/assets/images/' . $nama;
            move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
            $gambar = $nama;
        }

        $this->paket->simpan([
            'kode_paket'    => trim($_POST['kode_paket']),
            'nama_paket'    => trim($_POST['nama_paket']),
            'kategori'      => $_POST['kategori'],
            'harga'         => (float)str_replace('.', '', $_POST['harga']),
            'durasi_hari'   => (int)$_POST['durasi_hari'],
            'maskapai'      => trim($_POST['maskapai']),
            'hotel_mekkah'  => trim($_POST['hotel_mekkah']),
            'hotel_madinah' => trim($_POST['hotel_madinah']),
            'bintang_hotel' => (int)$_POST['bintang_hotel'],
            'kuota'         => (int)$_POST['kuota'],
            'fasilitas'     => trim($_POST['fasilitas']),
            'deskripsi'     => trim($_POST['deskripsi']),
            'gambar'        => $gambar,
            'status'        => $_POST['status'],
        ]);

        Session::flash('success', 'Paket berhasil ditambahkan.');
        header('Location: ' . BASE_URL . '/admin/paket');
        exit;
    }

    public function editPaket(?string $id): void {
        $data = $this->paket->getById((int)$id);
        if (!$data) { header('Location: ' . BASE_URL . '/admin/paket'); exit; }
        include BASE_PATH . '/app/views/admin/paket/edit.php';
    }

    public function updatePaket(): void {
        $id = (int)($_POST['id'] ?? 0);
        $this->paket->update($id, [
            'nama_paket'    => trim($_POST['nama_paket']),
            'kategori'      => $_POST['kategori'],
            'harga'         => (float)str_replace('.', '', $_POST['harga']),
            'durasi_hari'   => (int)$_POST['durasi_hari'],
            'maskapai'      => trim($_POST['maskapai']),
            'hotel_mekkah'  => trim($_POST['hotel_mekkah']),
            'hotel_madinah' => trim($_POST['hotel_madinah']),
            'bintang_hotel' => (int)$_POST['bintang_hotel'],
            'kuota'         => (int)$_POST['kuota'],
            'fasilitas'     => trim($_POST['fasilitas']),
            'deskripsi'     => trim($_POST['deskripsi']),
            'status'        => $_POST['status'],
        ]);
        Session::flash('success', 'Paket berhasil diperbarui.');
        header('Location: ' . BASE_URL . '/admin/paket');
        exit;
    }

    public function hapusPaket(?string $id): void {
        $this->paket->hapus((int)$id);
        Session::flash('success', 'Paket berhasil dihapus.');
        header('Location: ' . BASE_URL . '/admin/paket');
        exit;
    }

    // ── Kriteria ──────────────────────────────────────────────────
    public function daftarKriteria(): void {
        $kriteriaList = $this->kriteria->getWithSubKriteria();
        include BASE_PATH . '/app/views/admin/kriteria/index.php';
    }

    public function tambahKriteria(): void {
        include BASE_PATH . '/app/views/admin/kriteria/index.php';
    }

    public function simpanKriteria(): void {
        $tipe = $_POST['tipe'] ?? 'kriteria';
        if ($tipe === 'sub') {
            $this->kriteria->simpanSub([
                'kriteria_id' => (int)$_POST['kriteria_id'],
                'kode_sub'    => trim($_POST['kode_sub']),
                'nama_sub'    => trim($_POST['nama_sub']),
                'keterangan'  => trim($_POST['keterangan'] ?? ''),
            ]);
            Session::flash('success', 'Sub kriteria berhasil ditambahkan.');
        } else {
            $this->kriteria->simpan([
                'kode_kriteria' => trim($_POST['kode_kriteria']),
                'nama_kriteria' => trim($_POST['nama_kriteria']),
                'pertanyaan'    => trim($_POST['pertanyaan']),
                'urutan'        => (int)($_POST['urutan'] ?? 0),
            ]);
            Session::flash('success', 'Kriteria berhasil ditambahkan.');
        }
        header('Location: ' . BASE_URL . '/admin/kriteria');
        exit;
    }

    public function hapusKriteria(?string $id): void {
        $this->kriteria->hapus((int)$id);
        Session::flash('success', 'Kriteria berhasil dihapus.');
        header('Location: ' . BASE_URL . '/admin/kriteria');
        exit;
    }

    public function subKriteria(?string $id): void {
        // handled inline in kriteria view
        header('Location: ' . BASE_URL . '/admin/kriteria');
        exit;
    }

    // ── Aturan ────────────────────────────────────────────────────
    public function daftarAturan(): void {
        $aturanList   = $this->aturan->getAll();
        $paketList    = $this->paket->getAll();
        $kriteriaList = $this->kriteria->getWithSubKriteria();
        include BASE_PATH . '/app/views/admin/aturan/index.php';
    }

    public function tambahAturan(): void {
        $paketList    = $this->paket->getAll();
        $kriteriaList = $this->kriteria->getWithSubKriteria();
        include BASE_PATH . '/app/views/admin/aturan/tambah.php';
    }

    public function simpanAturan(): void {
        $subIds = array_map('intval', $_POST['sub_kriteria_ids'] ?? []);
        $kode   = $this->aturan->generateKode();
        $this->aturan->simpan([
            'kode_aturan' => $kode,
            'paket_id'    => (int)$_POST['paket_id'],
            'deskripsi'   => trim($_POST['deskripsi'] ?? ''),
            'bobot'       => (float)($_POST['bobot'] ?? 1.0),
        ], $subIds);
        Session::flash('success', 'Aturan berhasil ditambahkan.');
        header('Location: ' . BASE_URL . '/admin/aturan');
        exit;
    }

    public function hapusAturan(?string $id): void {
        $this->aturan->hapus((int)$id);
        Session::flash('success', 'Aturan berhasil dihapus.');
        header('Location: ' . BASE_URL . '/admin/aturan');
        exit;
    }

    // ── Laporan ───────────────────────────────────────────────────
    public function laporan(): void {
        $konsultasiList = $this->konsultasi->getAll(100);
        include BASE_PATH . '/app/views/admin/laporan/index.php';
    }

    public function detailKonsultasi(?string $id): void {
        $konsData = $this->konsultasi->getById((int)$id);
        $hasil    = $this->konsultasi->getHasil((int)$id);
        $jawaban  = $this->konsultasi->getJawabanByKonsultasiId((int)$id);
        include BASE_PATH . '/app/views/admin/laporan/index.php';
    }

    // ── API ───────────────────────────────────────────────────────
    public function apiSubKriteria(?string $kriteriaId): void {
        $data = $this->kriteria->getSubByKriteriaId((int)$kriteriaId);
        echo json_encode($data);
    }
}