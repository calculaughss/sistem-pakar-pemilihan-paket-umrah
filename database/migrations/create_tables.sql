-- ============================================================
-- FILE  : database/migrations/create_tables.sql
-- DESC  : DDL — membuat semua tabel untuk SPK Umrah
-- Jalankan file ini PERTAMA sebelum seed_data.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS spk_umrah
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE spk_umrah;

-- ── 1. users ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id           INT          NOT NULL AUTO_INCREMENT,
    nama         VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    password     VARCHAR(255) NOT NULL,
    role         ENUM('admin','user') NOT NULL DEFAULT 'user',
    no_hp        VARCHAR(20)  NULL,
    alamat       TEXT         NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
                              ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 2. paket_umrah ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS paket_umrah (
    id              INT            NOT NULL AUTO_INCREMENT,
    kode_paket      VARCHAR(20)    NOT NULL,
    nama_paket      VARCHAR(150)   NOT NULL,
    kategori        ENUM('reguler','plus','vip','furoda') NOT NULL,
    harga           DECIMAL(15,2)  NOT NULL,
    durasi_hari     INT            NOT NULL COMMENT 'Lama perjalanan (hari)',
    maskapai        VARCHAR(100)   NULL,
    hotel_mekkah    VARCHAR(100)   NULL,
    hotel_madinah   VARCHAR(100)   NULL,
    bintang_hotel   TINYINT        NOT NULL DEFAULT 3,
    kuota           INT            NOT NULL DEFAULT 0,
    fasilitas       TEXT           NULL COMMENT 'Daftar fasilitas dipisah koma',
    deskripsi       TEXT           NULL,
    gambar          VARCHAR(255)   NULL,
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    created_at      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                   ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_paket (kode_paket),
    KEY idx_kategori (kategori),
    KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 3. kriteria ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS kriteria (
    id              INT         NOT NULL AUTO_INCREMENT,
    kode_kriteria   VARCHAR(10) NOT NULL,
    nama_kriteria   VARCHAR(150) NOT NULL,
    pertanyaan      TEXT         NOT NULL COMMENT 'Teks pertanyaan ke user',
    urutan          INT          NOT NULL DEFAULT 0,
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_kriteria (kode_kriteria),
    KEY idx_urutan (urutan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 4. sub_kriteria ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS sub_kriteria (
    id              INT         NOT NULL AUTO_INCREMENT,
    kriteria_id     INT         NOT NULL,
    kode_sub        VARCHAR(20) NOT NULL,
    nama_sub        VARCHAR(150) NOT NULL,
    keterangan      TEXT         NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_sub (kode_sub),
    KEY idx_kriteria_id (kriteria_id),
    CONSTRAINT fk_sub_kriteria
        FOREIGN KEY (kriteria_id) REFERENCES kriteria(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 5. aturan ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS aturan (
    id              INT          NOT NULL AUTO_INCREMENT,
    kode_aturan     VARCHAR(20)  NOT NULL,
    paket_id        INT          NOT NULL COMMENT 'Paket yang direkomendasikan (THEN)',
    deskripsi       TEXT         NULL,
    bobot           DECIMAL(5,2) NOT NULL DEFAULT 1.00 COMMENT 'Bobot kepercayaan 0–1',
    status          ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_aturan (kode_aturan),
    KEY idx_paket_id (paket_id),
    CONSTRAINT fk_aturan_paket
        FOREIGN KEY (paket_id) REFERENCES paket_umrah(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 6. kondisi_aturan ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS kondisi_aturan (
    id              INT NOT NULL AUTO_INCREMENT,
    aturan_id       INT NOT NULL,
    sub_kriteria_id INT NOT NULL COMMENT 'Premis IF yang harus terpenuhi',
    PRIMARY KEY (id),
    UNIQUE KEY uq_aturan_sub (aturan_id, sub_kriteria_id),
    KEY idx_aturan_id      (aturan_id),
    KEY idx_sub_kriteria_id (sub_kriteria_id),
    CONSTRAINT fk_kondisi_aturan
        FOREIGN KEY (aturan_id) REFERENCES aturan(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_kondisi_sub
        FOREIGN KEY (sub_kriteria_id) REFERENCES sub_kriteria(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 7. konsultasi ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS konsultasi (
    id               INT          NOT NULL AUTO_INCREMENT,
    user_id          INT          NULL,
    kode_konsultasi  VARCHAR(40)  NOT NULL,
    nama_konsultan   VARCHAR(100) NULL COMMENT 'Nama jika tidak login',
    no_hp            VARCHAR(20)  NULL,
    status           ENUM('proses','selesai') NOT NULL DEFAULT 'proses',
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kode_konsultasi (kode_konsultasi),
    KEY idx_user_id  (user_id),
    KEY idx_status   (status),
    CONSTRAINT fk_konsultasi_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 8. jawaban_user ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS jawaban_user (
    id               INT       NOT NULL AUTO_INCREMENT,
    konsultasi_id    INT       NOT NULL,
    kriteria_id      INT       NOT NULL,
    sub_kriteria_id  INT       NOT NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_kons_kriteria (konsultasi_id, kriteria_id)
               COMMENT 'Satu kriteria hanya bisa dijawab sekali per sesi',
    KEY idx_konsultasi_id   (konsultasi_id),
    KEY idx_kriteria_id     (kriteria_id),
    KEY idx_sub_kriteria_id (sub_kriteria_id),
    CONSTRAINT fk_jawaban_konsultasi
        FOREIGN KEY (konsultasi_id) REFERENCES konsultasi(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_jawaban_kriteria
        FOREIGN KEY (kriteria_id) REFERENCES kriteria(id),
    CONSTRAINT fk_jawaban_sub
        FOREIGN KEY (sub_kriteria_id) REFERENCES sub_kriteria(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 9. hasil_rekomendasi ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS hasil_rekomendasi (
    id               INT          NOT NULL AUTO_INCREMENT,
    konsultasi_id    INT          NOT NULL,
    paket_id         INT          NOT NULL,
    aturan_terpicu   TEXT         NULL COMMENT 'JSON array kode aturan yang terpicu',
    skor             DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Persentase kesesuaian',
    keterangan       TEXT         NULL,
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_konsultasi_hasil (konsultasi_id),
    KEY idx_paket_id (paket_id),
    CONSTRAINT fk_hasil_konsultasi
        FOREIGN KEY (konsultasi_id) REFERENCES konsultasi(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_hasil_paket
        FOREIGN KEY (paket_id) REFERENCES paket_umrah(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
