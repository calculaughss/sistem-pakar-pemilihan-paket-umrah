<?php
class AuthController {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                Session::set('user_id', $user['id']);
                Session::set('nama',    $user['nama']);
                Session::set('email',   $user['email']);
                Session::set('role',    $user['role']);

                if ($user['role'] === 'admin') {
                    header('Location: ' . BASE_URL . '/admin/dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/home');
                }
                exit;
            }

            Session::flash('error', 'Email atau password salah.');
        }

        include BASE_PATH . '/app/views/auth/login.php';
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama     = trim($_POST['nama'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $no_hp    = trim($_POST['no_hp'] ?? '');

            // Cek email duplikat
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                Session::flash('error', 'Email sudah terdaftar.');
                include BASE_PATH . '/app/views/auth/register.php';
                return;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt2 = $this->db->prepare(
                "INSERT INTO users (nama, email, password, no_hp, role) VALUES (?,?,?,'user',?)"
            );
            $stmt2->bind_param('ssss', $nama, $email, $hash, $no_hp);
            $stmt2->execute();

            Session::flash('success', 'Registrasi berhasil! Silakan login.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        include BASE_PATH . '/app/views/auth/register.php';
    }

    public function logout(): void {
        Session::destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}