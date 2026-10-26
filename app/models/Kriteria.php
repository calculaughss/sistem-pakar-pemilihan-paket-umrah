<?php
class Kriteria {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $result = $this->db->query(
            "SELECT * FROM kriteria WHERE status = 'aktif' ORDER BY urutan ASC"
        );
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM kriteria WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getWithSubKriteria(): array {
        $kriteria = $this->getAll();
        foreach ($kriteria as &$k) {
            $k['sub_kriteria'] = $this->getSubByKriteriaId($k['id']);
        }
        return $kriteria;
    }

    public function getSubByKriteriaId(int $kriteriaId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM sub_kriteria WHERE kriteria_id = ? ORDER BY id ASC"
        );
        $stmt->bind_param('i', $kriteriaId);
        $stmt->execute();
        $data = [];
        $res  = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getSubById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM sub_kriteria WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function simpan(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO kriteria (kode_kriteria, nama_kriteria, pertanyaan, urutan)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param('sssi', $data['kode_kriteria'], $data['nama_kriteria'], $data['pertanyaan'], $data['urutan']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function simpanSub(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan)
             VALUES (?,?,?,?)"
        );
        $stmt->bind_param('isss', $data['kriteria_id'], $data['kode_sub'], $data['nama_sub'], $data['keterangan']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function hapus(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM kriteria WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function hapusSub(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM sub_kriteria WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) as n FROM kriteria")->fetch_assoc()['n'];
    }
}