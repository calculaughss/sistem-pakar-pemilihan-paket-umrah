<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/spk-umrah');

session_start();

require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/helpers/Session.php';
require_once BASE_PATH . '/app/helpers/ForwardChaining.php';
require_once BASE_PATH . '/app/models/Paket.php';
require_once BASE_PATH . '/app/models/Kriteria.php';
require_once BASE_PATH . '/app/models/Konsultasi.php';
require_once BASE_PATH . '/app/models/Aturan.php';
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/PaketController.php';
require_once BASE_PATH . '/app/controllers/KonsultasiController.php';
require_once BASE_PATH . '/app/controllers/HasilController.php';
require_once BASE_PATH . '/app/controllers/AdminController.php';
require_once BASE_PATH . '/app/routes/web.php';