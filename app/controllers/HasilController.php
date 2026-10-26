<?php
class HasilController {
    private Konsultasi $konsultasi;

    public function __construct() {
        $this->konsultasi = new Konsultasi();
    }

    public function tampilkan(?string $id): void {
        if (!$id) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $konsultasiId = (int)$id;
        $konsData     = $this->konsultasi->getById($konsultasiId);

        if (!$konsData) {
            http_response_code(404);
            include BASE_PATH . '/app/views/layouts/404.php';
            return;
        }

        $hasil    = $this->konsultasi->getHasil($konsultasiId);
        $jawaban  = $this->konsultasi->getJawabanByKonsultasiId($konsultasiId);

        // Decode aturan terpicu
        $aturanTerpicu = $hasil ? json_decode($hasil['aturan_terpicu'], true) : [];

        include BASE_PATH . '/app/views/user/hasil.php';
    }
}