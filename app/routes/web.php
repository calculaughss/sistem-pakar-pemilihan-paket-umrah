<?php
$url = trim($_GET['url'] ?? '', '/');
$segments = explode('/', $url);

$page       = $segments[0] ?? '';
$action     = $segments[1] ?? 'index';
$param      = $segments[2] ?? null;

$auth    = new AuthController();
$paket   = new PaketController();
$konsul  = new KonsultasiController();
$hasil   = new HasilController();
$admin   = new AdminController();

switch ($page) {
    // ── Auth ──────────────────────────────────────────
    case '':
    case 'home':
        $paket->home();
        break;
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;

    // ── User: Konsultasi ──────────────────────────────
    case 'konsultasi':
        match ($action) {
            'mulai'    => $konsul->mulai(),
            'pertanyaan' => $konsul->pertanyaan(),
            'jawab'    => $konsul->jawab(),
            'hasil'    => $hasil->tampilkan($param),
            default    => $konsul->mulai(),
        };
        break;

    // ── User: Paket ───────────────────────────────────
    case 'paket':
        match ($action) {
            'index'  => $paket->index(),
            'detail' => $paket->detail($param),
            default  => $paket->index(),
        };
        break;

    // ── Admin ─────────────────────────────────────────
    case 'admin':
        Session::requireAdmin();
        match ($action) {
            'dashboard'  => $admin->dashboard(),
            'paket'      => match ($param) {
                'tambah'  => $admin->tambahPaket(),
                'simpan'  => $admin->simpanPaket(),
                'edit'    => $admin->editPaket($segments[3] ?? null),
                'update'  => $admin->updatePaket(),
                'hapus'   => $admin->hapusPaket($segments[3] ?? null),
                default   => $admin->daftarPaket(),
            },
            'kriteria'   => match ($param) {
                'tambah'  => $admin->tambahKriteria(),
                'simpan'  => $admin->simpanKriteria(),
                'hapus'   => $admin->hapusKriteria($segments[3] ?? null),
                'sub'     => $admin->subKriteria($segments[3] ?? null),
                default   => $admin->daftarKriteria(),
            },
            'aturan'     => match ($param) {
                'tambah'  => $admin->tambahAturan(),
                'simpan'  => $admin->simpanAturan(),
                'hapus'   => $admin->hapusAturan($segments[3] ?? null),
                default   => $admin->daftarAturan(),
            },
            'laporan'    => $admin->laporan(),
            'konsultasi' => $admin->detailKonsultasi($param),
            default      => $admin->dashboard(),
        };
        break;

    // ── API (AJAX) ────────────────────────────────────
    case 'api':
        header('Content-Type: application/json');
        match ($action) {
            'pertanyaan'   => $konsul->apiPertanyaan(),
            'simpan-jawab' => $konsul->apiSimpanJawab(),
            'inferensi'    => $konsul->apiInferensi(),
            'sub-kriteria' => $admin->apiSubKriteria($param),
            default        => http_response_code(404),
        };
        break;

    default:
        http_response_code(404);
        include BASE_PATH . '/app/views/layouts/404.php';
        break;
}