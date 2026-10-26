-- ============================================================
-- FILE  : database/spk_umrah.sql
-- DESC  : Full dump — struktur + data awal
--         SPK Pemilihan Paket Umrah Persada Al-Amin
--         Metode: Forward Chaining | PHP + JavaScript + MySQL
--
-- Cara import:
--   mysql -u root -p < database/spk_umrah.sql
--   atau import melalui phpMyAdmin
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- Hapus database lama jika ada, lalu buat ulang
DROP DATABASE IF EXISTS spk_umrah;
CREATE DATABASE spk_umrah
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE spk_umrah;

-- ============================================================
-- BAGIAN 1 — STRUKTUR TABEL
-- ============================================================

CREATE TABLE users (
    id           INT          NOT NULL AUTO_INCREMENT,
    nama         VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    password     VARCHAR(255) NOT NULL,
    role         ENUM('admin','user') NOT NULL DEFAULT 'user',
    no_hp        VARCHAR(20)  NULL,
    alamat       TEXT         NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE paket_umrah (
    id              INT            NOT NULL AUTO_INCREMENT,
    kode_paket      VARCHAR(20)    NOT NULL,
    nama_paket      VARCHAR(150)   NOT NULL,
    kategori        ENUM('reguler','plus','vip','furoda') NOT NULL,
    harga           DECIMAL(15,2)  NOT NULL,
    durasi_hari     INT            NOT NULL,
    maskapai        VARCHAR(100)   NULL,
    hotel_mekkah    VARCHAR(100)   NULL,
    hotel_madinah   VARCHAR(100)   NULL,
    bintang_hotel   TINYINT        NOT NULL DEFAULT 3,
    kuota           INT            NOT NULL DEFAULT 0,
    fasilitas       TEXT           NULL,
    deskripsi       TEXT           NULL,
    gambar          VARCHAR(255)   NULL,
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    created_at      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_paket (kode_paket)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE kriteria (
    id              INT          NOT NULL AUTO_INCREMENT,
    kode_kriteria   VARCHAR(10)  NOT NULL,
    nama_kriteria   VARCHAR(150) NOT NULL,
    pertanyaan      TEXT         NOT NULL,
    urutan          INT          NOT NULL DEFAULT 0,
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_kriteria (kode_kriteria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sub_kriteria (
    id              INT          NOT NULL AUTO_INCREMENT,
    kriteria_id     INT          NOT NULL,
    kode_sub        VARCHAR(20)  NOT NULL,
    nama_sub        VARCHAR(150) NOT NULL,
    keterangan      TEXT         NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_sub (kode_sub),
    KEY idx_kriteria_id (kriteria_id),
    CONSTRAINT fk_sub_kriteria FOREIGN KEY (kriteria_id)
        REFERENCES kriteria(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE aturan (
    id              INT          NOT NULL AUTO_INCREMENT,
    kode_aturan     VARCHAR(20)  NOT NULL,
    paket_id        INT          NOT NULL,
    deskripsi       TEXT         NULL,
    bobot           DECIMAL(5,2) NOT NULL DEFAULT 1.00,
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_aturan (kode_aturan),
    CONSTRAINT fk_aturan_paket FOREIGN KEY (paket_id)
        REFERENCES paket_umrah(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE kondisi_aturan (
    id              INT NOT NULL AUTO_INCREMENT,
    aturan_id       INT NOT NULL,
    sub_kriteria_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_aturan_sub (aturan_id, sub_kriteria_id),
    CONSTRAINT fk_kondisi_aturan FOREIGN KEY (aturan_id)
        REFERENCES aturan(id) ON DELETE CASCADE,
    CONSTRAINT fk_kondisi_sub FOREIGN KEY (sub_kriteria_id)
        REFERENCES sub_kriteria(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE konsultasi (
    id               INT          NOT NULL AUTO_INCREMENT,
    user_id          INT          NULL,
    kode_konsultasi  VARCHAR(40)  NOT NULL,
    nama_konsultan   VARCHAR(100) NULL,
    no_hp            VARCHAR(20)  NULL,
    status           ENUM('proses','selesai') NOT NULL DEFAULT 'proses',
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_konsultasi (kode_konsultasi),
    CONSTRAINT fk_konsultasi_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE jawaban_user (
    id               INT       NOT NULL AUTO_INCREMENT,
    konsultasi_id    INT       NOT NULL,
    kriteria_id      INT       NOT NULL,
    sub_kriteria_id  INT       NOT NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kons_kriteria (konsultasi_id, kriteria_id),
    CONSTRAINT fk_jawaban_konsultasi FOREIGN KEY (konsultasi_id)
        REFERENCES konsultasi(id) ON DELETE CASCADE,
    CONSTRAINT fk_jawaban_kriteria FOREIGN KEY (kriteria_id)
        REFERENCES kriteria(id),
    CONSTRAINT fk_jawaban_sub FOREIGN KEY (sub_kriteria_id)
        REFERENCES sub_kriteria(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE hasil_rekomendasi (
    id               INT          NOT NULL AUTO_INCREMENT,
    konsultasi_id    INT          NOT NULL,
    paket_id         INT          NOT NULL,
    aturan_terpicu   TEXT         NULL,
    skor             DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    keterangan       TEXT         NULL,
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_konsultasi_hasil (konsultasi_id),
    CONSTRAINT fk_hasil_konsultasi FOREIGN KEY (konsultasi_id)
        REFERENCES konsultasi(id) ON DELETE CASCADE,
    CONSTRAINT fk_hasil_paket FOREIGN KEY (paket_id)
        REFERENCES paket_umrah(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- BAGIAN 2 — DATA AWAL
-- ============================================================

-- Admin (password: admin123)
INSERT INTO users (nama, email, password, role) VALUES
('Administrator', 'admin@persadaalamin.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Paket
INSERT INTO paket_umrah (kode_paket,nama_paket,kategori,harga,durasi_hari,maskapai,hotel_mekkah,hotel_madinah,bintang_hotel,kuota,fasilitas,status) VALUES
('PKT001','Paket Umrah Reguler 9 Hari','reguler',22500000,9,'Lion Air','Al Safwah Royale Orchid','Anwar Al Madinah Movenpick',3,40,'Tiket PP,Visa Umrah,Muthawif,Hotel Bintang 3,Transportasi Darat','aktif'),
('PKT002','Paket Umrah Reguler 12 Hari','reguler',27000000,12,'Batik Air','Zam Zam Tower','Grand Mercure Madinah',3,35,'Tiket PP,Visa Umrah,Muthawif,Hotel Bintang 3,Tur Ziarah,Perlengkapan Umrah','aktif'),
('PKT003','Paket Umrah Plus 12 Hari','plus',35000000,12,'Garuda Indonesia','Hilton Suites Makkah','Oberoi Madinah',4,30,'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,City Tour,Air Zam-Zam 5L','aktif'),
('PKT004','Paket Umrah Plus 14 Hari','plus',42000000,14,'Garuda Indonesia','Grand Zam Zam Makkah','Grand Mercure Madinah',4,25,'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,City Tour,Air Zam-Zam 5L,Koper Eksklusif','aktif'),
('PKT005','Paket Umrah VIP 14 Hari','vip',55000000,14,'Saudia Airlines','Raffles Makkah Palace','Angsana Medinah',5,20,'Tiket PP Bisnis,Visa Umrah,Muthawif Privat,Hotel Bintang 5,Tur Eksklusif,Lounge Bandara,Air Zam-Zam 10L','aktif'),
('PKT006','Paket Umrah VIP 16 Hari','vip',65000000,16,'Emirates Airlines','Jabal Omar Hyatt Regency','Angsana Medinah',5,15,'Tiket PP Emirates Bisnis,Visa Umrah,Muthawif Privat,Hotel Bintang 5 Dekat Haram,Tur Eksklusif,Transfer Privat','aktif'),
('PKT007','Paket Umrah Furoda Eksklusif','furoda',95000000,12,'Saudia Airlines','Swissotel Al Maqam Makkah','Crowne Plaza Madinah',5,10,'Visa Furoda (Mujamalah),Tiket PP Bisnis,Hotel Bintang 5,Muthawif Privat,Layanan VIP Bandara,Air Zam-Zam 20L','aktif'),
('PKT008','Paket Umrah Ramadan Plus 14 Hari','plus',48000000,14,'Garuda Indonesia','Hilton Suites Makkah','Grand Mercure Madinah',4,20,'Tiket PP Garuda,Visa Umrah,Muthawif,Hotel Bintang 4,Tur Ziarah,Buka Puasa Bersama,Sahur,Air Zam-Zam 10L','aktif');

-- Kriteria
INSERT INTO kriteria (kode_kriteria,nama_kriteria,pertanyaan,urutan) VALUES
('K01','Anggaran','Berapa kisaran anggaran yang Anda siapkan untuk paket umrah?',1),
('K02','Durasi Perjalanan','Berapa lama durasi perjalanan umrah yang Anda inginkan?',2),
('K03','Fasilitas','Fasilitas seperti apa yang Anda prioritaskan dalam paket umrah?',3),
('K04','Maskapai','Apakah Anda memiliki preferensi maskapai penerbangan?',4),
('K05','Hotel','Berapa bintang hotel yang Anda inginkan selama di Tanah Suci?',5),
('K06','Waktu Keberangkatan','Kapan rencana keberangkatan umrah Anda?',6);

-- Sub Kriteria
INSERT INTO sub_kriteria (kriteria_id,kode_sub,nama_sub,keterangan) VALUES
(1,'K01_A','Di bawah Rp 25 juta','Paket ekonomi'),
(1,'K01_B','Rp 25 juta – Rp 35 juta','Paket standar'),
(1,'K01_C','Rp 35 juta – Rp 55 juta','Paket menengah'),
(1,'K01_D','Di atas Rp 55 juta','Paket premium / furoda'),
(2,'K02_A','9 hari','Singkat & padat'),
(2,'K02_B','12 hari','Durasi standar'),
(2,'K02_C','14 hari','Lebih banyak waktu ibadah'),
(2,'K02_D','16 hari atau lebih','Durasi panjang & santai'),
(3,'K03_A','Standar (tiket, visa, hotel)','Fasilitas dasar'),
(3,'K03_B','Standar + tur ziarah','Dengan kunjungan situs bersejarah'),
(3,'K03_C','Lengkap + muthawif + city tour','Fasilitas lengkap dengan panduan'),
(3,'K03_D','Full service premium (muthawif privat, lounge, dll)','Layanan VIP total'),
(4,'K04_A','Tidak ada preferensi','Maskapai mana saja'),
(4,'K04_B','Garuda Indonesia','Maskapai nasional Indonesia'),
(4,'K04_C','Saudia Airlines','Maskapai Arab Saudi'),
(4,'K04_D','Emirates / Qatar Airways','Maskapai internasional premium'),
(5,'K05_A','Bintang 3','Nyaman & terjangkau'),
(5,'K05_B','Bintang 4','Fasilitas lebih baik'),
(5,'K05_C','Bintang 5','Mewah & eksklusif'),
(5,'K05_D','Bintang 5 & dekat Masjidil Haram','Lokasi strategis mewah'),
(6,'K06_A','Bulan biasa (di luar Ramadan)','Harga normal'),
(6,'K06_B','Bulan Ramadan atau akhir tahun','Musim peak, harga premium'),
(6,'K06_C','Fleksibel, menyesuaikan','Kapan saja tersedia');

-- Aturan
INSERT INTO aturan (kode_aturan,paket_id,deskripsi,bobot) VALUES
('R001',1,'Anggaran <25jt, 9 hari, standar → Reguler 9 Hari',1.00),
('R002',2,'Anggaran 25-35jt, 12 hari, standar+ziarah → Reguler 12 Hari',1.00),
('R003',3,'Anggaran 35-55jt, Garuda, 12 hari, bintang 4 → Plus 12 Hari',1.00),
('R004',4,'Anggaran 35-55jt, 14 hari, lengkap, bintang 4 → Plus 14 Hari',1.00),
('R005',5,'Anggaran >55jt, Saudia, bintang 5, full service → VIP 14 Hari',1.00),
('R006',6,'Anggaran >55jt, Emirates, bintang 5 dekat Haram, 16+ hari → VIP 16 Hari',1.00),
('R007',7,'Anggaran >55jt, full service premium, Saudia, dekat Haram → Furoda',1.00),
('R008',8,'Keberangkatan Ramadan, menengah, Garuda, bintang 4 → Ramadan Plus',1.00),
('R009',1,'Anggaran <25jt, fleksibel, standar → Reguler 9 Hari',0.80),
('R010',4,'Anggaran menengah, maskapai bebas, 14 hari, bintang 4 → Plus 14 Hari',0.90);

-- Kondisi Aturan
INSERT INTO kondisi_aturan (aturan_id,sub_kriteria_id) VALUES
-- R001
(1,1),(1,5),(1,9),
-- R002
(2,2),(2,6),(2,10),
-- R003
(3,3),(3,6),(3,14),(3,18),
-- R004
(4,3),(4,7),(4,11),(4,18),
-- R005
(5,4),(5,15),(5,19),(5,12),
-- R006
(6,4),(6,16),(6,20),(6,8),
-- R007
(7,4),(7,12),(7,15),(7,20),
-- R008
(8,22),(8,3),(8,14),(8,18),
-- R009
(9,1),(9,23),(9,9),
-- R010
(10,3),(10,13),(10,7),(10,18);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SELESAI — Akun admin: admin@persadaalamin.com / admin123
-- ============================================================
