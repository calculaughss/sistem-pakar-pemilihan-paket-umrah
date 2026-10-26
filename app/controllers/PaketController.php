<?php
class PaketController {
    private Paket $paket;

    public function __construct() {
        $this->paket = new Paket();
    }

    public function home(): void {
        $paketList = $this->paket->getAll();
        include BASE_PATH . '/app/views/user/home.php';
    }

    public function index(): void {
        $paketList = $this->paket->getAll();
        include BASE_PATH . '/app/views/user/home.php';
    }

    public function detail(?string $id): void {
        if (!$id) {
            header('Location: ' . BASE_URL . '/paket');
            exit;
        }
        $data = $this->paket->getById((int)$id);
        if (!$data) {
            http_response_code(404);
            include BASE_PATH . '/app/views/layouts/404.php';
            return;
        }
        include BASE_PATH . '/app/views/user/paket-detail.php';
    }
}