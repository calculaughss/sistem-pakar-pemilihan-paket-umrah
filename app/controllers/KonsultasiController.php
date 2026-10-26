<?php
class KonsultasiController {
    private Konsultasi $konsultasi;
    private Kriteria   $kriteria;
    private ForwardChaining $fc;

    public function __construct() {
        $this->konsultasi = new Konsultasi();
        $this->kriteria   = new Kriteria();
        $this->fc         = new ForwardChaining();
    }

    /** Halaman mulai konsultasi (form nama & no HP) */
    public function mulai(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama  = trim($_POST['nama'] ?? '');
            $no_hp = trim($_POST['no_hp'] ?? '');
            $userId = Session::isLoggedIn() ? Session::get('user_id') : null;

            if (empty($nama)) {
                Session::flash('error', 'Nama wajib diisi.');
                include BASE_PATH . '/app/views/user/konsultasi.php';
                return;
            }

            $kode = ForwardChaining::generateKodeKonsultasi();
            $konsId = $this->konsultasi->buat([
                'user_id'         => $userId,
                'kode_konsultasi' => $kode,
                'nama_konsultan'  => $nama,
                'no_hp'           => $no_hp,
            ]);

            Session::set('konsultasi_id', $konsId);
            Session::set('konsultasi_kode', $kode);
            Session::set('pertanyaan_ke', 0);

            header('Location: ' . BASE_URL . '/konsultasi/pertanyaan');
            exit;
        }

        include BASE_PATH . '/app/views/user/konsultasi.php';
    }

    /** Halaman tanya jawab step by step */
    public function pertanyaan(): void {
        $konsultasiId = Session::get('konsultasi_id');
        if (!$konsultasiId) {
            header('Location: ' . BASE_URL . '/konsultasi/mulai');
            exit;
        }

        $kriteriaList = $this->kriteria->getWithSubKriteria();
        $step         = Session::get('pertanyaan_ke', 0);

        if ($step >= count($kriteriaList)) {
            header('Location: ' . BASE_URL . '/api/inferensi');
            exit;
        }

        $kriteriaSekarang = $kriteriaList[$step];
        $totalKriteria    = count($kriteriaList);

        include BASE_PATH . '/app/views/user/pertanyaan.php';
    }

    /** Proses simpan jawaban via POST biasa */
    public function jawab(): void {
        $konsultasiId = Session::get('konsultasi_id');
        $kriteriaId   = (int)($_POST['kriteria_id'] ?? 0);
        $subId        = (int)($_POST['sub_kriteria_id'] ?? 0);
        $step         = Session::get('pertanyaan_ke', 0);

        if (!$konsultasiId || !$kriteriaId || !$subId) {
            header('Location: ' . BASE_URL . '/konsultasi/pertanyaan');
            exit;
        }

        $this->konsultasi->simpanJawaban($konsultasiId, $kriteriaId, $subId);
        Session::set('pertanyaan_ke', $step + 1);

        $kriteriaList = $this->kriteria->getAll();
        if ($step + 1 >= count($kriteriaList)) {
            // Semua pertanyaan sudah dijawab — proses inferensi
            $fakta  = $this->konsultasi->getFaktaIds($konsultasiId);
            $hasil  = $this->fc->proses($fakta);

            if ($hasil['paket']) {
                $this->konsultasi->simpanHasil(
                    $konsultasiId,
                    $hasil['paket']['id'],
                    $hasil['aturan_terpicu'],
                    $hasil['skor'],
                    'Diproses otomatis oleh sistem Forward Chaining'
                );
                $this->konsultasi->selesaikan($konsultasiId);
            }

            header('Location: ' . BASE_URL . '/konsultasi/hasil/' . $konsultasiId);
            exit;
        }

        header('Location: ' . BASE_URL . '/konsultasi/pertanyaan');
        exit;
    }

    // ── API (AJAX) ────────────────────────────────────────────────

    public function apiPertanyaan(): void {
        $step         = (int)($_GET['step'] ?? 0);
        $kriteriaList = $this->kriteria->getWithSubKriteria();

        if ($step >= count($kriteriaList)) {
            echo json_encode(['selesai' => true]);
            return;
        }

        echo json_encode([
            'selesai'  => false,
            'step'     => $step,
            'total'    => count($kriteriaList),
            'kriteria' => $kriteriaList[$step],
        ]);
    }

    public function apiSimpanJawab(): void {
        $input        = json_decode(file_get_contents('php://input'), true);
        $konsultasiId = Session::get('konsultasi_id');
        $kriteriaId   = (int)($input['kriteria_id'] ?? 0);
        $subId        = (int)($input['sub_kriteria_id'] ?? 0);

        if (!$konsultasiId) {
            http_response_code(401);
            echo json_encode(['error' => 'Sesi tidak ditemukan']);
            return;
        }

        $this->konsultasi->simpanJawaban($konsultasiId, $kriteriaId, $subId);
        echo json_encode(['success' => true]);
    }

    public function apiInferensi(): void {
        $konsultasiId = Session::get('konsultasi_id');
        if (!$konsultasiId) {
            echo json_encode(['error' => 'Sesi tidak ditemukan']);
            return;
        }

        $fakta = $this->konsultasi->getFaktaIds($konsultasiId);
        $hasil = $this->fc->proses($fakta);

        if ($hasil['paket']) {
            $this->konsultasi->simpanHasil(
                $konsultasiId,
                $hasil['paket']['id'],
                $hasil['aturan_terpicu'],
                $hasil['skor']
            );
            $this->konsultasi->selesaikan($konsultasiId);
        }

        echo json_encode(['redirect' => BASE_URL . '/konsultasi/hasil/' . $konsultasiId]);
    }
}