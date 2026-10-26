<?php
class Konsultasi {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function buat(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO konsultasi (user_id, kode_konsultasi, nama_konsultan, no_hp, status)
             VALUES (?,?,?,?,'proses')"
        );
        $stmt->bind_param('isss', $data['user_id'], $data['kode_konsultasi'], $data['nama_konsultan'], $data['no_hp']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM konsultasi WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByKode(string $kode): ?array {
        $stmt = $this->db->prepare("SELECT * FROM konsultasi WHERE kode_konsultasi = ?");
        $stmt->bind_param('s', $kode);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAll(int $limit = 20, int $offset = 0): array {
        $result = $this->db->query(
            "SELECT k.*, u.nama as nama_user
             FROM konsultasi k
             LEFT JOIN users u ON u.id = k.user_id
             ORDER BY k.created_at DESC
             LIMIT $limit OFFSET $offset"
        );
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function simpanJawaban(int $konsultasiId, int $kriteriaId, int $subKriteriaId): bool {
        // Upsert: ganti jawaban jika kriteria sudah dijawab sebelumnya
        $stmt = $this->db->prepare(
            "INSERT INTO jawaban_user (konsultasi_id, kriteria_id, sub_kriteria_id)
             VALUES (?,?,?)
             ON DUPLICATE KEY UPDATE sub_kriteria_id = VALUES(sub_kriteria_id)"
        );
        $stmt->bind_param('iii', $konsultasiId, $kriteriaId, $subKriteriaId);
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function getJawabanByKonsultasiId(int $konsultasiId): array {
        $stmt = $this->db->prepare(
            "SELECT ju.*, sk.kode_sub, sk.nama_sub, k.nama_kriteria
             FROM jawaban_user ju
             JOIN sub_kriteria sk ON sk.id = ju.sub_kriteria_id
             JOIN kriteria k ON k.id = ju.kriteria_id
             WHERE ju.konsultasi_id = ?"
        );
        $stmt->bind_param('i', $konsultasiId);
        $stmt->execute();
        $data = [];
        $res  = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getFaktaIds(int $konsultasiId): array {
        $jawaban = $this->getJawabanByKonsultasiId($konsultasiId);
        return array_column($jawaban, 'sub_kriteria_id');
    }

    public function simpanHasil(int $konsultasiId, int $paketId, array $aturanTerpicu, float $skor, string $keterangan = ''): int {
        $aturanJson = json_encode($aturanTerpicu);
        $stmt = $this->db->prepare(
            "INSERT INTO hasil_rekomendasi (konsultasi_id, paket_id, aturan_terpicu, skor, keterangan)
             VALUES (?,?,?,?,?)
             ON DUPLICATE KEY UPDATE paket_id=VALUES(paket_id), aturan_terpicu=VALUES(aturan_terpicu), skor=VALUES(skor)"
        );
        $stmt->bind_param('iisds', $konsultasiId, $paketId, $aturanJson, $skor, $keterangan);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function getHasil(int $konsultasiId): ?array {
        $stmt = $this->db->prepare(
            "SELECT hr.*, p.nama_paket, p.kategori, p.harga, p.durasi_hari,
                    p.maskapai, p.hotel_mekkah, p.hotel_madinah, p.bintang_hotel,
                    p.fasilitas, p.gambar
             FROM hasil_rekomendasi hr
             JOIN paket_umrah p ON p.id = hr.paket_id
             WHERE hr.konsultasi_id = ?"
        );
        $stmt->bind_param('i', $konsultasiId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function selesaikan(int $konsultasiId): void {
        $stmt = $this->db->prepare(
            "UPDATE konsultasi SET status = 'selesai' WHERE id = ?"
        );
        $stmt->bind_param('i', $konsultasiId);
        $stmt->execute();
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) as n FROM konsultasi")->fetch_assoc()['n'];
    }

    public function countSelesai(): int {
        return (int) $this->db->query("SELECT COUNT(*) as n FROM konsultasi WHERE status='selesai'")->fetch_assoc()['n'];
    }
}