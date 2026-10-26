<?php
class Paket {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(string $status = 'aktif'): array {
        $status = $this->db->escape($status);
        $result = $this->db->query(
            "SELECT * FROM paket_umrah WHERE status = '$status' ORDER BY harga ASC"
        );
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM paket_umrah WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByKategori(string $kategori): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_umrah WHERE kategori = ? AND status = 'aktif' ORDER BY harga ASC"
        );
        $stmt->bind_param('s', $kategori);
        $stmt->execute();
        $data = [];
        $res  = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function simpan(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO paket_umrah
             (kode_paket, nama_paket, kategori, harga, durasi_hari, maskapai,
              hotel_mekkah, hotel_madinah, bintang_hotel, kuota, fasilitas, deskripsi, gambar, status)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->bind_param(
            'sssdiisssiisss',
            $data['kode_paket'], $data['nama_paket'], $data['kategori'],
            $data['harga'], $data['durasi_hari'], $data['maskapai'],
            $data['hotel_mekkah'], $data['hotel_madinah'], $data['bintang_hotel'],
            $data['kuota'], $data['fasilitas'], $data['deskripsi'],
            $data['gambar'], $data['status']
        );
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE paket_umrah SET
             nama_paket=?, kategori=?, harga=?, durasi_hari=?, maskapai=?,
             hotel_mekkah=?, hotel_madinah=?, bintang_hotel=?, kuota=?,
             fasilitas=?, deskripsi=?, status=?
             WHERE id=?"
        );
        $stmt->bind_param(
            'ssdisssiiissi',
            $data['nama_paket'], $data['kategori'], $data['harga'],
            $data['durasi_hari'], $data['maskapai'], $data['hotel_mekkah'],
            $data['hotel_madinah'], $data['bintang_hotel'], $data['kuota'],
            $data['fasilitas'], $data['deskripsi'], $data['status'], $id
        );
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function hapus(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM paket_umrah WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $this->db->affectedRows() > 0;
    }

    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) as n FROM paket_umrah")->fetch_assoc()['n'];
    }
}