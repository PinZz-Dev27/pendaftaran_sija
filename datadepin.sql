-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Nov 2026 pada 03.24
-- Versi server: 10.4.21-MariaDB
-- Versi PHP: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datadepin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `sija2`
--

CREATE TABLE `sija2` (
  `NO` int(11) NOT NULL,
  `NAMA` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `WEBSITE` varchar(255) NOT NULL,
  `COMMENT` text NOT NULL,
  `GENDER` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sija2`
--

INSERT INTO `sija2` (`NO`, `NAMA`, `EMAIL`, `WEBSITE`, `COMMENT`, `GENDER`) VALUES
(2, 'Lutpi Makky', 'm3luthfi@gmail.com', 'sija.com', 'sija\r\nsija \r\nkeren', 'pria'),
(5, 'izzy', 'izzy7@gmail.com', 'izzy.com', 'izzy capskuy', 'pria'),
(6, 'devin', 'devinhardi27@gmail.com', 'sija.com', 'php keren', 'pria'),
(7, '', '', '', '', ''),
(8, '', '', '', '', ''),
(9, '', '', '', '', ''),
(10, '', '', '', '', ''),
(11, '', '', '', '', ''),
(12, 'Devin Hardi', 'devinhardi27@gmail.com', 'sija.com', 'sdw', 'male'),
(13, 'Devin Hardi', 'devinhardi27@gmail.com', 'sija.com', '', 'male'),
(14, '', '', '', '', ''),
(15, 'adelia', 'adeli@gmail.com', '', '', ''),
(16, 'ziww', 'ziww553@gmail.com', '', '', ''),
(17, 'ziww', 'ziww553@gmail.com', '', '', ''),
(18, 'ziww', 'ziww553@gmail.com', '', '', ''),
(19, 'ziww', 'ziww553@gmail.com', '', '', ''),
(20, 'ziww', 'ziww553@gmail.com', '', '', ''),
(21, 'ziww', 'ziww553@gmail.com', '', '', ''),
(22, 'ziww', 'ziww553@gmail.com', '', '', ''),
(23, 'ziww', 'ziww553@gmail.com', '', '', ''),
(24, 'ziww', 'ziww553@gmail.com', '', '', ''),
(25, 'Devin Hardiansyah', 'devinhardi27@gmail.com', 'https://github.com/devin/game-sija', '[JUDUL_GAME]: Cyber Network Defender\n[KELAS]: XI SIJA 2\n[GENRE]: 2D Platformer\n[ENGINE]: Godot Engine\n[PLATFORM]: PC / Windows\n[DESKRIPSI]: Game petualangan aksi siswa SIJA memberantas malware di jaringan sekolah.', 'Laki-laki'),
(26, 'Fiqrul Shafwaan', 'devinhardi27@gmail.com', 'https://tankbatle.netlify.app/', '[JUDUL_GAME]: tank battle\n[KELAS]: X SIJA 2\n[GENRE]: RPG (Role Playing Game)\n[ENGINE]: Unity\n[PLATFORM]: Android / Mobile\n[DESKRIPSI]: ', 'Laki-laki'),
(27, 'banyu tamvan', 'devinhardi27@gmail.com', '', '[JUDUL_GAME]: horor anget\n[KELAS]: XI SIJA 2\n[GENRE]: Horror & Mystery\n[ENGINE]: Unity\n[PLATFORM]: PC / Windows\n[DESKRIPSI]: ', 'Laki-laki');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `sija2`
--
ALTER TABLE `sija2`
  ADD PRIMARY KEY (`NO`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `sija2`
--
ALTER TABLE `sija2`
  MODIFY `NO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
