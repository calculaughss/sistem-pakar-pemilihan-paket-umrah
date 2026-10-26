-- ============================================================
-- FILE  : database/seeders/seed_data.sql
-- DESC  : Data awal untuk SPK Umrah Persada Al-Amin
-- Jalankan setelah create_tables.sql
-- ============================================================

USE spk_umrah;

-- ── Admin Default ─────────────────────────────────────────────
-- Password: admin123  (bcrypt hash)
INSERT INTO users (nama, email, password, role) VALUES
('Administrator', 'admin@persadaalamin.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE nama = VALUES(nama);

-- ── Paket Umrah ───────────────────────────────────────────────
INSERT INTO paket_umrah
    (kode_paket, nama_paket, kategori, harga, durasi_hari,
     maskapai, hotel_mekkah, hotel_madinah, bintang_hotel, kuota,
     fasilitas, deskripsi, status)
VALUES
('PKT001', 'Paket Umrah Reguler 9 Hari', 'reguler', 22500000, 9,
 'Lion Air', 'Al Safwah Royale Orchid', 'Anwar Al Madinah Movenpick', 3, 40,
 'Tiket PP,Visa Umrah,Muthawif,Hotel Bintang 3,Transportasi Darat',
 'Paket hemat cocok untuk Anda yang ingin menunaikan ibadah umrah dengan anggaran terjangkau. Penerbangan langsung Kuala Lumpur – Jeddah. Hotel nyaman bintang 3 dekat Masjidil Haram.', 'aktif'),

('PKT002', 'Paket Umrah Reguler 12 Hari', 'reguler', 27000000, 12,
 'Batik Air', 'Zam Zam Tower', 'Grand Mercure Madinah', 3, 35,
 'Tiket PP,Visa Umrah,Muthawif,Hotel Bintang 3,Tur Ziarah,Perlengkapan Umrah',
 'Paket reguler dengan durasi lebih panjang. Tersedia waktu ziarah ke situs bersejarah Islam di Mekkah dan Madinah. Cocok untuk jamaah yang ingin lebih banyak beribadah.', 'aktif'),

('PKT003', 'Paket Umrah Plus 12 Hari', 'plus', 35000000, 12,
 'Garuda Indonesia', 'Hilton Suites Makkah', 'Oberoi Madinah', 4, 30,
 'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,City Tour,Air Zam-Zam 5L',
 'Paket plus dengan maskapai nasional kebanggaan Garuda Indonesia. Hotel bintang 4 dengan fasilitas lebih lengkap. Termasuk tur ziarah dan city tour Mekkah–Madinah.', 'aktif'),

('PKT004', 'Paket Umrah Plus 14 Hari', 'plus', 42000000, 14,
 'Garuda Indonesia', 'Grand Zam Zam Makkah', 'Grand Mercure Madinah', 4, 25,
 'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,City Tour,Air Zam-Zam 5L,Koper Eksklusif',
 'Paket plus dengan durasi 14 hari memberikan lebih banyak waktu untuk beribadah. Fasilitas lengkap dengan hotel bintang 4 pilihan dan berbagai tur religi.', 'aktif'),

('PKT005', 'Paket Umrah VIP 14 Hari', 'vip', 55000000, 14,
 'Saudia Airlines', 'Raffles Makkah Palace', 'Angsana Medinah', 5, 20,
 'Tiket PP Bisnis,Visa Umrah,Muthawif Privat,Hotel Bintang 5,Tur Eksklusif,Lounge Bandara,Air Zam-Zam 10L,Oleh-Oleh Premium',
 'Pengalaman umrah mewah dengan layanan kelas satu. Penerbangan Saudia Airlines kelas bisnis, hotel bintang 5 pilihan, dan muthawif privat yang berpengalaman.', 'aktif'),

('PKT006', 'Paket Umrah VIP 16 Hari', 'vip', 65000000, 16,
 'Emirates Airlines', 'Jabal Omar Hyatt Regency', 'Angsana Medinah', 5, 15,
 'Tiket PP Emirates Bisnis,Visa Umrah,Muthawif Privat,Hotel Bintang 5 Dekat Haram,Tur Eksklusif,Lounge Premium,Transfer Privat',
 'Paket VIP premium dengan Emirates Airlines kelas bisnis. Hotel Jabal Omar Hyatt Regency berjarak hanya 50 meter dari Masjidil Haram. Layanan transfer privat.', 'aktif'),

('PKT007', 'Paket Umrah Furoda Eksklusif', 'furoda', 95000000, 12,
 'Saudia Airlines', 'Swissotel Al Maqam Makkah', 'Crowne Plaza Madinah', 5, 10,
 'Visa Furoda (Mujamalah),Tiket PP Bisnis,Hotel Bintang 5,Muthawif Privat,Layanan VIP Bandara,Air Zam-Zam 20L,Souvenir Eksklusif',
 'Paket Furoda menggunakan Visa Mujamalah (undangan pemerintah Arab Saudi) sehingga tidak perlu antri haji. Tersedia sepanjang tahun termasuk musim haji. Fasilitas serba premium.', 'aktif'),

('PKT008', 'Paket Umrah Ramadan Plus 14 Hari', 'plus', 48000000, 14,
 'Garuda Indonesia', 'Hilton Suites Makkah', 'Grand Mercure Madinah', 4, 20,
 'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,Buka Puasa Bersama,Sahur,Air Zam-Zam 10L',
 'Paket spesial untuk umrah di bulan Ramadan. Merasakan suasana Ramadan di Tanah Suci yang penuh keberkahan. Tersedia layanan sahur dan buka puasa di hotel.', 'aktif')
ON DUPLICATE KEY UPDATE nama_paket = VALUES(nama_paket);

-- ── Kriteria ─────────────────────────────────────────────────
INSERT INTO kriteria (kode_kriteria, nama_kriteria, pertanyaan, urutan) VALUES
('K01', 'Anggaran',          'Berapa kisaran anggaran yang Anda siapkan untuk paket umrah?', 1),
('K02', 'Durasi Perjalanan', 'Berapa lama durasi perjalanan umrah yang Anda inginkan?', 2),
('K03', 'Fasilitas',         'Fasilitas seperti apa yang Anda prioritaskan dalam paket umrah?', 3),
('K04', 'Maskapai',          'Apakah Anda memiliki preferensi maskapai penerbangan?', 4),
('K05', 'Hotel',             'Berapa bintang hotel yang Anda inginkan selama di Tanah Suci?', 5),
('K06', 'Waktu Keberangkatan','Kapan rencana keberangkatan umrah Anda?', 6)
ON DUPLICATE KEY UPDATE nama_kriteria = VALUES(nama_kriteria);

-- ── Sub Kriteria K01: Anggaran ────────────────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K01'), 'K01_A', 'Di bawah Rp 25 juta',        'Paket ekonomi'),
((SELECT id FROM kriteria WHERE kode_kriteria='K01'), 'K01_B', 'Rp 25 juta – Rp 35 juta',    'Paket standar'),
((SELECT id FROM kriteria WHERE kode_kriteria='K01'), 'K01_C', 'Rp 35 juta – Rp 55 juta',    'Paket menengah'),
((SELECT id FROM kriteria WHERE kode_kriteria='K01'), 'K01_D', 'Di atas Rp 55 juta',          'Paket premium / furoda')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Sub Kriteria K02: Durasi ──────────────────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K02'), 'K02_A', '9 hari',           'Singkat & padat'),
((SELECT id FROM kriteria WHERE kode_kriteria='K02'), 'K02_B', '12 hari',          'Durasi standar'),
((SELECT id FROM kriteria WHERE kode_kriteria='K02'), 'K02_C', '14 hari',          'Lebih banyak waktu ibadah'),
((SELECT id FROM kriteria WHERE kode_kriteria='K02'), 'K02_D', '16 hari atau lebih','Durasi panjang & santai')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Sub Kriteria K03: Fasilitas ───────────────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K03'), 'K03_A', 'Standar (tiket, visa, hotel)',              'Fasilitas dasar'),
((SELECT id FROM kriteria WHERE kode_kriteria='K03'), 'K03_B', 'Standar + tur ziarah',                     'Dengan kunjungan situs bersejarah'),
((SELECT id FROM kriteria WHERE kode_kriteria='K03'), 'K03_C', 'Lengkap + muthawif + city tour',            'Fasilitas lengkap dengan panduan'),
((SELECT id FROM kriteria WHERE kode_kriteria='K03'), 'K03_D', 'Full service premium (muthawif privat, lounge, dll)', 'Layanan VIP total')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Sub Kriteria K04: Maskapai ────────────────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K04'), 'K04_A', 'Tidak ada preferensi',      'Maskapai mana saja'),
((SELECT id FROM kriteria WHERE kode_kriteria='K04'), 'K04_B', 'Garuda Indonesia',           'Maskapai nasional Indonesia'),
((SELECT id FROM kriteria WHERE kode_kriteria='K04'), 'K04_C', 'Saudia Airlines',            'Maskapai Arab Saudi'),
((SELECT id FROM kriteria WHERE kode_kriteria='K04'), 'K04_D', 'Emirates / Qatar Airways',   'Maskapai internasional premium')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Sub Kriteria K05: Hotel ───────────────────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K05'), 'K05_A', 'Bintang 3',                       'Nyaman & terjangkau'),
((SELECT id FROM kriteria WHERE kode_kriteria='K05'), 'K05_B', 'Bintang 4',                       'Fasilitas lebih baik'),
((SELECT id FROM kriteria WHERE kode_kriteria='K05'), 'K05_C', 'Bintang 5',                       'Mewah & eksklusif'),
((SELECT id FROM kriteria WHERE kode_kriteria='K05'), 'K05_D', 'Bintang 5 & dekat Masjidil Haram','Lokasi strategis mewah')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Sub Kriteria K06: Waktu Keberangkatan ─────────────────────
INSERT INTO sub_kriteria (kriteria_id, kode_sub, nama_sub, keterangan) VALUES
((SELECT id FROM kriteria WHERE kode_kriteria='K06'), 'K06_A', 'Bulan biasa (di luar Ramadan)',   'Harga normal'),
((SELECT id FROM kriteria WHERE kode_kriteria='K06'), 'K06_B', 'Bulan Ramadan atau akhir tahun',  'Musim peak, harga premium'),
((SELECT id FROM kriteria WHERE kode_kriteria='K06'), 'K06_C', 'Fleksibel, menyesuaikan',         'Kapan saja tersedia')
ON DUPLICATE KEY UPDATE nama_sub = VALUES(nama_sub);

-- ── Aturan Forward Chaining ───────────────────────────────────
-- Setiap aturan: IF [kondisi] THEN [paket]

-- R001: Anggaran rendah + 9 hari + standar → PKT001
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R001', id, 'Anggaran <25jt, durasi 9 hari, fasilitas standar → Reguler 9 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT001'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R001'), id FROM sub_kriteria WHERE kode_sub IN ('K01_A','K02_A','K03_A');

-- R002: Anggaran 25-35jt + 12 hari + standar+ziarah → PKT002
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R002', id, 'Anggaran 25-35jt, durasi 12 hari, standar+ziarah → Reguler 12 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT002'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R002'), id FROM sub_kriteria WHERE kode_sub IN ('K01_B','K02_B','K03_B');

-- R003: 35-55jt + Garuda + 12 hari + bintang 4 → PKT003
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R003', id, 'Anggaran 35-55jt, Garuda, 12 hari, bintang 4 → Plus 12 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT003'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R003'), id FROM sub_kriteria WHERE kode_sub IN ('K01_C','K02_B','K04_B','K05_B');

-- R004: 35-55jt + 14 hari + lengkap + bintang 4 → PKT004
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R004', id, 'Anggaran 35-55jt, 14 hari, fasilitas lengkap, bintang 4 → Plus 14 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT004'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R004'), id FROM sub_kriteria WHERE kode_sub IN ('K01_C','K02_C','K03_C','K05_B');

-- R005: >55jt + Saudia + bintang 5 + full service → PKT005
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R005', id, 'Anggaran >55jt, Saudia, bintang 5, full service → VIP 14 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT005'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R005'), id FROM sub_kriteria WHERE kode_sub IN ('K01_D','K04_C','K05_C','K03_D');

-- R006: >55jt + Emirates + bintang 5 dekat haram + 16 hari → PKT006
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R006', id, 'Anggaran >55jt, Emirates, bintang 5 dekat Haram, 16+ hari → VIP 16 Hari', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT006'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R006'), id FROM sub_kriteria WHERE kode_sub IN ('K01_D','K04_D','K05_D','K02_D');

-- R007: >55jt + full service + Saudia + bintang 5 dekat haram → PKT007 (Furoda)
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R007', id, 'Anggaran >55jt, full service premium, Saudia, dekat Haram → Furoda', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT007'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R007'), id FROM sub_kriteria WHERE kode_sub IN ('K01_D','K03_D','K04_C','K05_D');

-- R008: Ramadan + 35-55jt + Garuda + bintang 4 → PKT008
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R008', id, 'Keberangkatan Ramadan, anggaran menengah, Garuda, bintang 4 → Ramadan Plus', 1.00
FROM paket_umrah WHERE kode_paket = 'PKT008'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R008'), id FROM sub_kriteria WHERE kode_sub IN ('K06_B','K01_C','K04_B','K05_B');

-- R009: Anggaran rendah + fleksibel + 9 hari (alternatif R001)
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R009', id, 'Anggaran <25jt, waktu fleksibel, standar → Reguler 9 Hari', 0.80
FROM paket_umrah WHERE kode_paket = 'PKT001'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R009'), id FROM sub_kriteria WHERE kode_sub IN ('K01_A','K06_C','K03_A');

-- R010: Anggaran menengah + tidak ada preferensi maskapai + 14 hari → PKT004
INSERT INTO aturan (kode_aturan, paket_id, deskripsi, bobot)
SELECT 'R010', id, 'Anggaran menengah, maskapai bebas, 14 hari → Plus 14 Hari', 0.90
FROM paket_umrah WHERE kode_paket = 'PKT004'
ON DUPLICATE KEY UPDATE deskripsi = VALUES(deskripsi);

INSERT IGNORE INTO kondisi_aturan (aturan_id, sub_kriteria_id)
SELECT (SELECT id FROM aturan WHERE kode_aturan='R010'), id FROM sub_kriteria WHERE kode_sub IN ('K01_C','K04_A','K02_C','K05_B');
