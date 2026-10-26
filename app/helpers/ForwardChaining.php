<?php
/**
 * ForwardChaining.php
 * Mesin inferensi Forward Chaining untuk SPK Pemilihan Paket Umrah
 *
 * Cara kerja:
 * 1. Ambil semua jawaban user (fakta)
 * 2. Cocokkan dengan kondisi setiap aturan (rule matching)
 * 3. Hitung skor kesesuaian tiap aturan
 * 4. Pilih aturan dengan skor tertinggi → rekomendasikan paket
 */
class ForwardChaining {
    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Proses inferensi utama
     *
     * @param array $fakta  Array sub_kriteria_id yang dipilih user
     * @return array        Hasil rekomendasi beserta detail proses
     */
    public function proses(array $fakta): array {
        $aturanList    = $this->getSemuaAturan();
        $hasilCocok    = [];
        $aturanTerpicu = [];

        foreach ($aturanList as $aturan) {
            $kondisi    = $this->getKondisiAturan($aturan['id']);
            $idKondisi  = array_column($kondisi, 'sub_kriteria_id');

            $jumlahKondisi = count($idKondisi);
            if ($jumlahKondisi === 0) continue;

            // Hitung berapa kondisi yang cocok dengan fakta
            $cocok = count(array_intersect($idKondisi, $fakta));
            $skor  = round(($cocok / $jumlahKondisi) * 100 * $aturan['bobot'], 2);

            if ($cocok > 0) {
                $hasilCocok[] = [
                    'aturan_id'    => $aturan['id'],
                    'kode_aturan'  => $aturan['kode_aturan'],
                    'paket_id'     => $aturan['paket_id'],
                    'deskripsi'    => $aturan['deskripsi'],
                    'jumlah_kondisi' => $jumlahKondisi,
                    'cocok'        => $cocok,
                    'skor'         => $skor,
                    'kondisi'      => $kondisi,
                ];
            }
        }

        if (empty($hasilCocok)) {
            // Fallback: rekomendasikan paket reguler pertama
            return $this->fallbackRekomendasi();
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasilCocok, fn($a, $b) => $b['skor'] <=> $a['skor']);

        $terbaik = $hasilCocok[0];
        $paket   = $this->getPaketById($terbaik['paket_id']);

        // Kumpulkan semua aturan yang terpicu (skor > 0)
        foreach ($hasilCocok as $h) {
            $aturanTerpicu[] = $h['kode_aturan'];
        }

        return [
            'status'           => 'success',
            'paket'            => $paket,
            'skor'             => $terbaik['skor'],
            'aturan_terbaik'   => $terbaik,
            'aturan_terpicu'   => $aturanTerpicu,
            'semua_hasil'      => $hasilCocok,
            'total_fakta'      => count($fakta),
        ];
    }

    /**
     * Ambil semua aturan aktif dari database
     */
    private function getSemuaAturan(): array {
        $hasil = [];
        $result = $this->db->query(
            "SELECT * FROM aturan WHERE status = 'aktif' ORDER BY id ASC"
        );
        while ($row = $result->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Ambil semua kondisi (premis) untuk satu aturan
     */
    private function getKondisiAturan(int $aturanId): array {
        $hasil = [];
        $stmt  = $this->db->prepare(
            "SELECT ka.sub_kriteria_id, sk.kode_sub, sk.nama_sub, k.nama_kriteria
             FROM kondisi_aturan ka
             JOIN sub_kriteria sk ON sk.id = ka.sub_kriteria_id
             JOIN kriteria k ON k.id = sk.kriteria_id
             WHERE ka.aturan_id = ?"
        );
        $stmt->bind_param('i', $aturanId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Ambil data paket berdasarkan ID
     */
    private function getPaketById(int $paketId): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM paket_umrah WHERE id = ? AND status = 'aktif'"
        );
        $stmt->bind_param('i', $paketId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Fallback jika tidak ada aturan yang cocok
     */
    private function fallbackRekomendasi(): array {
        $paket = $this->db->query(
            "SELECT * FROM paket_umrah WHERE status = 'aktif' ORDER BY harga ASC LIMIT 1"
        )->fetch_assoc();

        return [
            'status'         => 'fallback',
            'paket'          => $paket,
            'skor'           => 0,
            'aturan_terbaik' => null,
            'aturan_terpicu' => [],
            'semua_hasil'    => [],
            'total_fakta'    => 0,
        ];
    }

    /**
     * Generate kode konsultasi unik
     */
    public static function generateKodeKonsultasi(): string {
        return 'KNS-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . date('Ymd');
    }
}