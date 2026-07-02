CREATE DATABASE IF NOT EXISTS pegawaiku;
USE pegawaiku;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','pimpinan','pegawai') NOT NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE pegawai (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  nip VARCHAR(100) NULL,
  nama VARCHAR(255) NOT NULL,
  jabatan VARCHAR(255) NULL,
  unit_kerja VARCHAR(255) NULL,
  status_kepegawaian ENUM('PNS','PPPK','Kontrak','Honorer') NULL,
  tanggal_mulai DATE NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_pegawai_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE cuti (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pegawai_id BIGINT UNSIGNED NOT NULL,
  jenis ENUM('Tahunan','Sakit','Melahirkan','Alasan Penting') NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  alasan TEXT NOT NULL,
  status ENUM('Diajukan','Disetujui','Ditolak') NOT NULL DEFAULT 'Diajukan',
  catatan_pimpinan TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_cuti_pegawai FOREIGN KEY (pegawai_id) REFERENCES pegawai(id) ON DELETE CASCADE
);

CREATE TABLE program (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  keterangan TEXT NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  tahun INT NOT NULL,
  anggaran BIGINT NOT NULL DEFAULT 0,
  realisasi BIGINT NOT NULL DEFAULT 0,
  sisa_anggaran BIGINT NOT NULL DEFAULT 0,
  status_approval ENUM('Diajukan','Disetujui','Ditolak','Revisi') NOT NULL DEFAULT 'Diajukan',
  tahap ENUM('Perencanaan','Berjalan','Selesai','Dibatalkan') NOT NULL DEFAULT 'Perencanaan',
  penanggung_jawab_id BIGINT UNSIGNED NULL,
  diajukan_oleh BIGINT UNSIGNED NULL,
  catatan_pimpinan TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_program_pj FOREIGN KEY (penanggung_jawab_id) REFERENCES pegawai(id) ON DELETE SET NULL,
  CONSTRAINT fk_program_pengaju FOREIGN KEY (diajukan_oleh) REFERENCES pegawai(id) ON DELETE SET NULL
);

CREATE TABLE anggaran (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_id BIGINT UNSIGNED NOT NULL,
  kode VARCHAR(100) NULL,
  total BIGINT NOT NULL DEFAULT 0,
  realisasi BIGINT NOT NULL DEFAULT 0,
  sisa BIGINT NOT NULL DEFAULT 0,
  tahun INT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_anggaran_program FOREIGN KEY (program_id) REFERENCES program(id) ON DELETE CASCADE
);

CREATE TABLE prestasi (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pegawai_id BIGINT UNSIGNED NOT NULL,
  kategori ENUM('Penghargaan','Apresiasi','Tugas Luar Biasa','Inovasi') NOT NULL,
  tanggal DATE NOT NULL,
  deskripsi TEXT NOT NULL,
  pemberi VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_prestasi_pegawai FOREIGN KEY (pegawai_id) REFERENCES pegawai(id) ON DELETE CASCADE
);

CREATE TABLE master_anggaran (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  total BIGINT NOT NULL DEFAULT 0,
  sisa BIGINT NOT NULL DEFAULT 0,
  keterangan VARCHAR(255) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
