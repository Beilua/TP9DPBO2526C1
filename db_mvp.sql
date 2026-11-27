-- ============================================================
-- Database: db_mvp
-- Tugas Praktikum DPBO Modul 10 - Model View Presenter (MVP)
-- Import file ini melalui phpMyAdmin atau:
--   mysql -u root -p < mvp_db.sql
-- Catatan: file ini menghapus tabel kandidat dan lowongan lebih
-- dahulu agar hasil import ulang selalu konsisten.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_mvp`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `db_mvp`;
SET NAMES utf8mb4;

DROP TABLE IF EXISTS `lowongan`;
DROP TABLE IF EXISTS `kandidat`;

CREATE TABLE `kandidat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `domisili` varchar(100) NOT NULL,
  `skorTes` int(11) NOT NULL DEFAULT 0,
  `pengalamanTahun` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `tipeKerja` varchar(50) NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `status` varchar(20) NOT NULL DEFAULT 'Buka',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kandidat` (`id`, `nama`, `posisi`, `domisili`, `skorTes`, `pengalamanTahun`) VALUES
(1, 'Raka Pratama', 'Backend Developer', 'Bandung', 92, 3),
(2, 'Sinta Maharani', 'UI/UX Designer', 'Jakarta', 88, 2),
(3, 'Dimas Nugroho', 'Data Analyst', 'Yogyakarta', 85, 1),
(4, 'Nadia Kusuma', 'Frontend Developer', 'Surabaya', 90, 2),
(5, 'Bagas Setiawan', 'QA Engineer', 'Bandung', 81, 1);

INSERT INTO `lowongan` (`id`, `judul`, `departemen`, `lokasi`, `tipeKerja`, `kuota`, `status`) VALUES
(1, 'Backend Developer', 'Teknologi Informasi', 'Bandung', 'Full-time', 2, 'Buka'),
(2, 'UI/UX Designer', 'Desain Produk', 'Jakarta', 'Kontrak', 1, 'Buka'),
(3, 'Data Analyst Intern', 'Data & Analytics', 'Yogyakarta', 'Magang', 3, 'Buka'),
(4, 'QA Engineer', 'Teknologi Informasi', 'Surabaya', 'Full-time', 1, 'Tutup');
