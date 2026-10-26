<?php
class Aturan {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $result = $this->db->query(
            "SELECT a.*, p.nama_paket, p.kategori
             FROM aturan a
             JOIN paket_umrah p ON p.id = a.paket_id
             ORDER BY a.id ASC"
        );
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['kondisi'] = $this->getKondisi($row['id']);
            $data[] = $row;
        }
        return $data;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.nama_paket FROM aturan a
             JOIN paket_umrah p ON p.id = a.paket_id
             WHERE a.id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) $row['kondisi'] = $this->getKondisi($id);
        return $row;
    }

    public function getKondisi(int $aturanId): array {
        $stmt = $this->db->prepare(
            "SELECT ka.*, sk.kode_sub, sk.nama_sub, k.nama_kriteria
             FROM kondisi_aturan ka
             JOIN sub_kriteria sk ON sk.id = ka.sub_kriteria_id
             JOIN kriteria k ON k.id = sk.kriteria_id
             WHERE ka.aturan_id = ?"
        );
        $stmt->bind_param('i', $aturanId);
        $stmt->execute();
        $data = [];
        $res  = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function simpan(array $data, array $subKriteriaIds): int {
        $stmt = $this->db->prepare(
            "INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot) VALUES (?,?,?,?)"
        );
        $stmt->bind_param('sisd', $data['kode_aturan'], $data['paket_id'], $data['deskripsi'], $data['bobot']);
        $stmt->execute();
        $aturanId = $this->db->lastInsertId();

        foreach ($subKriteriaIds as $subId) {
            $stmt2 = $this->db->prepare(
                "INSERT INTO kondisi_aturan (aturan_id, sub_kriteria_id) VALUES (?,?)"
            );
            $stmt2->bind_param('ii', $aturanId, $subId);
            $stmt2->execute();
        }

        return $aturanId;
    }

    public function hapus(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM aturan WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function generateKode(): string {
        $result = $this->db->query("SELECT COUNT(*) as n FROM aturan");
        $n = (int) $result->fetch_assoc()['n'];
        return 'R' . str_pad($n + 1, 3, '0', STR_PAD_LEFT);
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) as n FROM aturan")->fetch_assoc()['n'];
    }
}