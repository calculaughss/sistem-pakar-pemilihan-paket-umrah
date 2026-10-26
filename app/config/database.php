<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'spk_umrah');

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die(json_encode([
                'status' => 'error',
                'message' => 'Koneksi database gagal: ' . $this->conn->connect_error
            ]));
        }
        $this->conn->set_charset('utf8mb4');
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli {
        return $this->conn;
    }

    public function query(string $sql): mysqli_result|bool {
        return $this->conn->query($sql);
    }

    public function prepare(string $sql): mysqli_stmt|false {
        return $this->conn->prepare($sql);
    }

    public function escape(string $value): string {
        return $this->conn->real_escape_string($value);
    }

    public function lastInsertId(): int {
        return $this->conn->insert_id;
    }

    public function affectedRows(): int {
        return $this->conn->affected_rows;
    }
}

$db = Database::getInstance();