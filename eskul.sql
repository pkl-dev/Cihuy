-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jul 2025 pada 09.41
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eskul`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `kehadiran` enum('Hadir','Izin','Sakit','Alfa') DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_ekskul` int(11) DEFAULT NULL,
  `id_pembina` int(11) DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `tanggal`, `kehadiran`, `id_siswa`, `id_ekskul`, `id_pembina`, `foto_bukti`) VALUES
(1, '2025-07-01', 'Hadir', 1, 1, NULL, 'foto1.jpg'),
(2, '2025-07-01', 'Izin', 2, 2, NULL, 'foto2.jpg'),
(3, '2025-07-01', 'Alfa', 3, 3, NULL, 'foto3.jpg'),
(4, '2025-07-01', 'Hadir', 4, 4, NULL, 'foto4.jpg'),
(5, '2025-07-01', 'Hadir', 5, 5, NULL, 'foto5.jpg'),
(6, '2025-07-09', 'Hadir', 1, 0, NULL, '17520431432825057426547377115808.jpg'),
(7, '2025-07-12', 'Izin', 1, 1, NULL, '1752299345_6871f75124f50.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `username`, `password`) VALUES
(1, 'Admin Utama', 'admin', '$2y$10$aZzPCpGkmFonpKXJyNf1zOBwpAk0S2bP6IyUk6sZlSdPbbRHWjjfi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ekskul`
--

CREATE TABLE `ekskul` (
  `id_ekskul` int(11) NOT NULL,
  `nama_ekskul` varchar(100) DEFAULT NULL,
  `id_pembina` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `ekskul`
--

INSERT INTO `ekskul` (`id_ekskul`, `nama_ekskul`, `id_pembina`) VALUES
(1, 'Paskibra', NULL),
(2, 'Pramuka', NULL),
(3, 'PMR', NULL),
(4, 'Futsal', 2),
(5, 'Tari', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `id_ekskul` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `hari`, `jam_mulai`, `jam_selesai`, `id_ekskul`) VALUES
(1, 'Senin', '15:00:00', '17:00:00', 1),
(2, 'Selasa', '15:00:00', '17:00:00', 2),
(3, 'Rabu', '15:30:00', '17:30:00', 3),
(4, 'Kamis', '14:00:00', '16:00:00', 4),
(5, 'Jumat', '13:00:00', '15:00:00', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kritik_saran`
--

CREATE TABLE `kritik_saran` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `tanggal_kirim` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `kritik_saran`
--

INSERT INTO `kritik_saran` (`id`, `id_siswa`, `pesan`, `tanggal_kirim`) VALUES
(1, 1, 'WAw bagus nya', '2025-07-12 12:49:52'),
(2, 7, 'lsajjhghnbvgf', '2025-07-12 14:24:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembina`
--

CREATE TABLE `pembina` (
  `id_pembina` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `pembina`
--

INSERT INTO `pembina` (`id_pembina`, `nama`, `username`, `password`, `nip`, `no_hp`, `alamat`, `jenis_kelamin`) VALUES
(1, 'Budi Santoso', 'budi_guru', '$2y$10$aZzPCpGkmFonpKXJyNf1zOBwpAk0S2bP6IyUk6sZlSdPbbRHWjjfi', '198012122002', '081234567890', 'Jl. Kenanga No. 10', 'L'),
(2, 'sudirja', 'uja', '$2y$10$/8Cqgw4Q2BhSQLpJFuNgrOZHerzDSX/YeHss0o2mt21hWiUEakQ2i', '19801212200212', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `kelas` varchar(10) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `username`, `password`, `nisn`, `kelas`, `jenis_kelamin`, `alamat`, `no_hp`) VALUES
(1, 'Rina Mai', 'siswa01', '$2y$10$CCKt2hgXtvLpHxLJc.jN1OR4POa8vlWvRY0NjZdt3BKrjxA24D17e', '0049328192', 'XI RPL', 'P', 'Jl. Anggrek No. 3', '081290000000'),
(2, 'Budi Santoso', 'siswa02', 'hashed_pass2', '0049328193', NULL, NULL, NULL, NULL),
(3, 'Citra Ayu', 'siswa03', 'hashed_pass3', '0049328194', NULL, NULL, NULL, NULL),
(5, 'Eko Saputra', 'siswa05', 'hashed_pass5', '0049328196', NULL, NULL, NULL, NULL),
(7, 'mai saroh', 'mai', '$2y$10$CH3Qajq6LagqiUenAHdqZ.0MspG0f3Wd6Jss9PkOIoh4X1EwBkBAm', '0049328192', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa_eskul`
--

CREATE TABLE `siswa_eskul` (
  `id_siswa_eskul` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_ekskul` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `siswa_eskul`
--

INSERT INTO `siswa_eskul` (`id_siswa_eskul`, `id_siswa`, `id_ekskul`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(5, 5, 5),
(6, 7, 4);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_ekskul` (`id_ekskul`),
  ADD KEY `id_pembina` (`id_pembina`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `ekskul`
--
ALTER TABLE `ekskul`
  ADD PRIMARY KEY (`id_ekskul`),
  ADD KEY `id_pembina` (`id_pembina`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_ekskul` (`id_ekskul`);

--
-- Indeks untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `pembina`
--
ALTER TABLE `pembina`
  ADD PRIMARY KEY (`id_pembina`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indeks untuk tabel `siswa_eskul`
--
ALTER TABLE `siswa_eskul`
  ADD PRIMARY KEY (`id_siswa_eskul`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_ekskul` (`id_ekskul`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `ekskul`
--
ALTER TABLE `ekskul`
  MODIFY `id_ekskul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kritik_saran`
--
ALTER TABLE `kritik_saran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pembina`
--
ALTER TABLE `pembina`
  MODIFY `id_pembina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `siswa_eskul`
--
ALTER TABLE `siswa_eskul`
  MODIFY `id_siswa_eskul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
