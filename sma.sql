-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 11:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sma`
--

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int(11) NOT NULL,
  `nama_divisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`) VALUES
(1, 'Olahraga'),
(2, 'Kesenian'),
(3, 'Olahraga dan Kesenian');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id_event` int(11) NOT NULL,
  `name_event` varchar(45) NOT NULL,
  `date_event` date DEFAULT curdate(),
  `detail_event` text DEFAULT NULL,
  `img_event` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id_event`, `name_event`, `date_event`, `detail_event`, `img_event`) VALUES
(12, 'Pengukuhan Wisuda Tahfizh Quran', '2025-08-17', 'Pengukuhan Wisuda Tahfizh Quran putera puteri SMA Negeri 2 Simeulue Barat.Alhamdulillah tahun ini diraih oleh 40 siswa-siswi anak ayah bunda masyarakat Simeulue Barat. Semoga menjadi generasi Qurani.', '1750310249_WhatsApp Image 2025-06-19 at 12.15.57.jpeg'),
(13, 'kegiatan 02SN ', '2025-12-10', 'Ajang kegiatan 02SN atau olimpiade olahraga siswa nasional 2025', '1750309748_WhatsApp Image 2025-06-19 at 12.06.11.jpeg'),
(14, 'Runner-up  Juara 2 Piala Pj Bupati Simeulue ', '2025-06-01', 'Liga Pelajar Siswa.Alhamdulillah wasyukurillah dapat sukses SMAN 2 SIMEULUE BARAT dari 34 SMA dan SMK sekabupaten Simeulue.', '1750311191_WhatsApp Image 2025-06-19 at 12.29.19.jpeg'),
(15, 'Kegiatan Pramuka Bakti', '2025-07-20', 'Aksi Galang dana tim Pramuka SMAN 2 SIMEULUE BARAT dari masyarakat sekitar atas musibah kebakaran pasar kampung air kecamatan Simeulue Tengah Kabupaten Simeulue.', '1750310698_WhatsApp Image 2025-06-19 at 12.23.40.jpeg'),
(16, 'Sosialisasi Masuk TNI', '2025-05-25', 'Sosialisasi Masuk TNI oleh Koramil Simbar untuk Murid kelas 12 .', '1750311493_WhatsApp Image 2025-06-19 at 12.36.22.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_pertanyaan`
--

CREATE TABLE `jawaban_pertanyaan` (
  `id_jawaban` int(11) NOT NULL,
  `id_registration` int(11) NOT NULL,
  `id_pertanyaan` int(11) NOT NULL,
  `jawaban` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jawaban_pertanyaan`
--

INSERT INTO `jawaban_pertanyaan` (`id_jawaban`, `id_registration`, `id_pertanyaan`, `jawaban`, `created_at`) VALUES
(6, 5, 11, 'tari', '2025-06-29 12:50:23'),
(7, 6, 16, 'tidak', '2025-07-01 03:15:58');

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_pertanyaan_opsi`
--

CREATE TABLE `jawaban_pertanyaan_opsi` (
  `id` int(11) NOT NULL,
  `id_jawaban` int(11) NOT NULL,
  `id_opsi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id_news` int(11) NOT NULL,
  `news_title` varchar(130) DEFAULT NULL,
  `news_content` text DEFAULT NULL,
  `news_datestamp` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id_news`, `news_title`, `news_content`, `news_datestamp`) VALUES
(13, 'Giat lomba olimpiade olahraga siswa nasional O2SN 2025', 'Giat lomba Olimpiade Olahraga Siswa Nasional ( O2SN) jenjang SMA Se Kabupaten Simeulue oleh para para peserta siswa siswi SMA Negeri Simeulue Barat tahun 2025. Raih prestasi demi tumbuh kembang tunas muda cabang Renang, Atletik , Bulu Tangkis, Karate dan Silat.', '2025-05-10'),
(14, 'Siswa SMA Negeri 2 Ikuti Lomba Debat Bahasa Indonesia', 'Tim debat SMA Negeri 2 berhasil masuk final lomba debat tingkat provinsi yang diselenggarakan oleh Dinas Pendidikan Aceh.', '2025-04-20'),
(15, 'Guru SMA Negeri 2 Terpilih Jadi Guru Teladan 2025', 'Ibu Rini, guru Biologi SMA Negeri 2 Simeulue Barat, mendapat penghargaan sebagai Guru Teladan tingkat provinsi pada tahun 2025.', '2025-05-01'),
(16, 'Peringatan Hari Kartini di SMA Negeri 2', 'SMA Negeri 2 Simeulue Barat mengadakan lomba fashion show dan puisi dalam rangka memperingati Hari Kartini pada tanggal 21 April.', '2025-04-21'),
(17, 'Sosialisasi Anti Narkoba oleh BNN di SMA Negeri 2', 'BNN kabupaten mengadakan sosialisasi bahaya narkoba kepada siswa SMA Negeri 2 sebagai bagian dari edukasi generasi muda bebas narkoba.', '2025-03-25');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notif` int(11) NOT NULL,
  `casis_id_notif` int(11) NOT NULL,
  `reg_id_notif` varchar(100) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `title_notif` varchar(200) NOT NULL,
  `message_notif` text NOT NULL,
  `read_notif` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id_notif`, `casis_id_notif`, `reg_id_notif`, `receiver_id`, `title_notif`, `message_notif`, `read_notif`, `created_at`) VALUES
(22, 23, 'REG20254367', 2, 'Pendaftaran Baru', 'Siswa <strong>fadia rahma</strong> telah melakukan pendaftaran dengan kode <strong>REG20254367</strong>.', 0, '2025-06-29 12:43:45'),
(23, 24, 'REG20259526', 2, 'Pendaftaran Baru', 'Siswa <strong>rahmat darmawan</strong> telah melakukan pendaftaran dengan kode <strong>REG20259526</strong>.', 0, '2025-06-29 13:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `opsi_pertanyaan`
--

CREATE TABLE `opsi_pertanyaan` (
  `id_opsi` int(11) NOT NULL,
  `id_pertanyaan` int(11) NOT NULL,
  `opsi_text` varchar(255) NOT NULL,
  `nilai_opsional` varchar(255) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opsi_pertanyaan`
--

INSERT INTO `opsi_pertanyaan` (`id_opsi`, `id_pertanyaan`, `opsi_text`, `nilai_opsional`, `urutan`, `created_at`, `updated_at`) VALUES
(10, 15, 'Ya', '', 1, '2025-06-03 04:52:23', '2025-06-03 04:52:23'),
(11, 15, 'Tidak', '', 2, '2025-06-03 04:52:23', '2025-06-03 04:52:23'),
(12, 15, 'Tergantung Waktu', '', 3, '2025-06-03 04:52:23', '2025-06-03 04:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_siswa`
--

CREATE TABLE `pendaftaran_siswa` (
  `id_registration` int(11) NOT NULL,
  `code_registration` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `place_birthdate` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `numphone` varchar(20) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `wali_name` varchar(100) NOT NULL,
  `nik` char(16) NOT NULL,
  `nisn` char(10) NOT NULL,
  `golongan_darah` enum('O','A','B','AB') NOT NULL,
  `asal_school` varchar(100) NOT NULL,
  `ijazah_number` varchar(45) NOT NULL,
  `pas_photo` varchar(255) NOT NULL,
  `ijasah_document` varchar(255) NOT NULL,
  `doc_tambahan` varchar(255) DEFAULT NULL,
  `sertifikat_divisi` varchar(255) DEFAULT NULL,
  `status` enum('pending','diterima','ditolak') NOT NULL DEFAULT 'pending',
  `tanggal_daftar` date NOT NULL DEFAULT curdate(),
  `casis_id_registration` int(11) NOT NULL,
  `id_divisi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran_siswa`
--

INSERT INTO `pendaftaran_siswa` (`id_registration`, `code_registration`, `full_name`, `place_birthdate`, `birthdate`, `address`, `numphone`, `father_name`, `mother_name`, `wali_name`, `nik`, `nisn`, `golongan_darah`, `asal_school`, `ijazah_number`, `pas_photo`, `ijasah_document`, `doc_tambahan`, `sertifikat_divisi`, `status`, `tanggal_daftar`, `casis_id_registration`, `id_divisi`) VALUES
(3, 'REG20257546', 'tuti', 'tuti', '2025-06-02', 'tuti', '3434324324324', 'tuti', 'tuti', 'tuti', '1234567890123456', '1234567890', 'O', 'tuti', '2131312312', 'pas_photo_683e7e2e6fe90.png', 'ijasah_document_683e7e2e6ff8e.pdf', NULL, NULL, 'ditolak', '2025-06-03', 6, 1),
(5, 'REG20254367', 'fadia rahma', 'sigulai', '2009-09-11', 'Sigulai', '082259021099', 'junarly', 'fita yuni', 'falhamsyah', '1109060911020009', '0107897587', 'O', 'smp 3 simeulue barat', '2131312312', 'foto_1751201257_fadia rahma.jpg', 'ijasah_document_6861350125678.pdf', 'doc_tambahan_6861350125f00.pdf', NULL, 'diterima', '2025-06-29', 23, 2),
(6, 'REG20259526', 'rahmat darmawan', 'sigulai', '2008-12-12', 'Sigulai', '082259021092', 'umar yahya', 'bila ', 'firdana', '1109060911020008', '0107897576', 'A', 'smp 3 simeulue barat', '2131312315', 'pas_photo_68613bbaaa5f0.jpg', 'ijasah_document_68613bbaaad85.pdf', 'doc_tambahan_68613bbaab4fa.pdf', NULL, 'pending', '2025-06-29', 24, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan_divisi`
--

CREATE TABLE `pertanyaan_divisi` (
  `id_pertanyaan` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `judul_pertanyaan` varchar(255) NOT NULL,
  `isi_pertanyaan` text NOT NULL,
  `tipe_pertanyaan` enum('text','textarea','radio','checkbox','select') NOT NULL,
  `is_required` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pertanyaan_divisi`
--

INSERT INTO `pertanyaan_divisi` (`id_pertanyaan`, `id_divisi`, `judul_pertanyaan`, `isi_pertanyaan`, `tipe_pertanyaan`, `is_required`, `created_at`, `updated_at`) VALUES
(11, 3, 'Jenis Kegiatan yang Diminati', 'Jika memilih Olahraga, jenis olahraga apa yang Anda minati?\r\n(contoh: sepak bola, basket, voli, pencak silat, dll.)\r\n\r\nJika memilih Kesenian, jenis kesenian apa yang Anda minati?\r\n(contoh: tari, musik, teater, lukis, paduan suara, dll.)', 'text', 1, '2025-06-03 04:50:03', '2025-06-03 04:50:03'),
(12, 1, 'Pengalaman Sebelumnya', 'Apakah Anda pernah mengikuti ekstrakurikuler atau perlombaan terkait bidang tersebut?\r\n\r\nJika Ya, jelaskan secara singkat:\r\nContoh: Pernah ikut lomba tari tingkat kabupaten tahun 2023.', 'text', 1, '2025-06-03 04:50:42', '2025-06-03 04:50:42'),
(13, 3, 'Alasan Bergabung', 'Mengapa Anda ingin bergabung dalam divisi ini?\r\n(Jawaban singkat 1–3 kalimat)', 'text', 1, '2025-06-03 04:50:56', '2025-06-03 04:50:56'),
(15, 3, 'Komitmen Waktu', 'Apakah Anda bersedia meluangkan waktu untuk latihan rutin dan kegiatan ekstrakurikuler di luar jam sekolah?', 'radio', 1, '2025-06-03 04:52:23', '2025-06-03 04:52:23'),
(16, 3, 'Peralatan Pribadi (opsional)', 'Apakah Anda memiliki alat pribadi yang mendukung kegiatan (misalnya: alat musik, kostum tari, sepatu olahraga khusus, dsb)?\r\nJika Ya, sebutkan:', 'text', 1, '2025-06-03 04:53:09', '2025-06-03 04:53:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','kepala_sekolah','siswa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(2, 'Admin', 'admin@gmail.com', '$2y$10$js2IgpZzoRLIIH9txKytuu9pYrkrQlhSaNK632tgmFe6d0p0RaOI6', 'admin'),
(3, 'kepala sekolah', 'sekolah@gmail.com', '$2y$10$8ZvE.OCLk8sHY3Httx9wmOZ0dsqNM1PqoQXwmNQh51FsBNZ0nithK', 'kepala_sekolah'),
(6, '', 'tuti@gmail.com', '$2y$10$PlQX.A91ngarXDa24WHESOF6Zt1U/BKkVnT2dlitA7mxSi1pnSvrG', 'siswa'),
(7, '', 'lulu@gmail.com', '$2y$10$iphxL3T0TrIR2F0cpsSYM.Zvqnzqb4QpV4DNznNo06fOA9nR.NsvK', 'siswa'),
(10, '', 'opi@gmail.com', '$2y$10$F8qY76Ij8enaFJa15hN22uC/vRkAeZl91Nsi5op.G2KjU9YQ.Fyvm', 'siswa'),
(11, '', 'juju@gmail.com', '$2y$10$ly4v2tothA85Qu9.yF/XS.J3oCr03DadT./B1XvXu4.7PezOBLl/S', 'siswa'),
(12, '', 'asdya@gmail.com', '$2y$10$lRSQ.DF2UqysfCdC9XpXCOuZx/UY4N6cdFnWDYmgPsX7EH3mKnXP6', 'siswa'),
(13, 'test1', 'test@gmail.com', '$2y$10$ce1AMrePG8mdJRqzDZfDgekPCxOgSylETQ7FSFHqG6RXc9qgqJU32', 'siswa'),
(14, 'syuxki', 'syuki@gmail.com', '$2y$10$vm5lYoFAOU4xi.yRJAXUrO5wGsjf5Wbhvmq9sc8BD.SKTl6TMF92i', 'siswa'),
(15, 'bayu', 'bayu@gmail.com', '$2y$10$NGZ0uMXMp.9n0wVzlDM6U.Jo4QsGvt3IjApnc5beC8aqDc/Z3VNcq', 'siswa'),
(16, 'mona', 'mona@gmail.com', '$2y$10$iTJX2X4J6wjr0.nNX59Nvevbn.yFxGCe0StvwvH7VQWTxdkgz.9..', 'siswa'),
(17, 'rudi', 'rudi@gmail.com', '$2y$10$gY3G94m5bkfl/wg2mV7Sr.i8MxsQamO.t0Y5A/e8Vq0p99Pw7buKi', 'siswa'),
(18, 'rose', 'rose@gmail.com', '$2y$10$w13K9y0r/oauqS4nqnl6hueTgjjRIOzs9iZiYPB.PF8X7IlXvoq8C', 'siswa'),
(19, 'baru', 'baru@gmail.com', '$2y$10$XPaTPy4EANcbVjZi62jRy.CuE8vvdKh1SZykss9YXDPVJotiEtJEK', 'siswa'),
(20, 'kancils', 'rifkirume@gmail.com', '$2y$10$ynBdRts7zMuuWyo4IztJheQzMzR80kmaSEhxW1UgSck7EWwZ4zZxq', 'siswa'),
(21, 'cut', 'cutm@gmail.com', '$2y$10$hNn4i1E.dlpCu8to7MYT9ulLwaNbbdqdx3bjBS79uGFzwL/Y4ueRq', 'siswa'),
(22, 'Tania Simamora', 'taniasimamora@gmail.com', '$2y$10$iBiZMa1KlbRuwZL/Tg4Yp.RoVbdnWoSyyxUFZ68R9qEio6La9mrGK', 'siswa'),
(23, 'fadia rahma', 'fadiarhma19@gmail.com', '$2y$10$ADs6gUrheJKQiRfCpAB3rO97oark7BPvgaqMCjrY416WW2MuX/qO.', 'siswa'),
(24, 'rahmat darmawan', 'rahmatcis19@gmail.com', '$2y$10$w4v0Bk14LjwDm3bQeUany.vdJ6Z32yJ5vve3U0WMJO4nEi9VWa0r6', 'siswa'),
(25, 'fadia rahma', 'fadia20@gmail.com', '$2y$10$J2uluEohRvBSRTZP49I6W.GvBvSvG1WGaqZoRfJ.PcC1idRNyviV.', 'siswa'),
(26, 'cindy cinta sari', 'cindymanies151@fmail.com', '$2y$10$3GWWcsv0VrLOa6xbx.G/beLqhbwhvVrviL1iagAb526XOqcaMP432', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `jawaban_pertanyaan`
--
ALTER TABLE `jawaban_pertanyaan`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD KEY `id_registration` (`id_registration`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`);

--
-- Indexes for table `jawaban_pertanyaan_opsi`
--
ALTER TABLE `jawaban_pertanyaan_opsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jawaban` (`id_jawaban`),
  ADD KEY `id_opsi` (`id_opsi`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id_news`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `fk_notifications_sender` (`casis_id_notif`),
  ADD KEY `fk_notifications_receiver` (`receiver_id`);

--
-- Indexes for table `opsi_pertanyaan`
--
ALTER TABLE `opsi_pertanyaan`
  ADD PRIMARY KEY (`id_opsi`),
  ADD KEY `id_pertanyaan` (`id_pertanyaan`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran_siswa`
--
ALTER TABLE `pendaftaran_siswa`
  ADD PRIMARY KEY (`id_registration`),
  ADD KEY `casis_id_registration` (`casis_id_registration`),
  ADD KEY `id_divisi` (`id_divisi`);

--
-- Indexes for table `pertanyaan_divisi`
--
ALTER TABLE `pertanyaan_divisi`
  ADD PRIMARY KEY (`id_pertanyaan`),
  ADD KEY `id_divisi` (`id_divisi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jawaban_pertanyaan`
--
ALTER TABLE `jawaban_pertanyaan`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jawaban_pertanyaan_opsi`
--
ALTER TABLE `jawaban_pertanyaan_opsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id_news` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `opsi_pertanyaan`
--
ALTER TABLE `opsi_pertanyaan`
  MODIFY `id_opsi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pendaftaran_siswa`
--
ALTER TABLE `pendaftaran_siswa`
  MODIFY `id_registration` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pertanyaan_divisi`
--
ALTER TABLE `pertanyaan_divisi`
  MODIFY `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jawaban_pertanyaan`
--
ALTER TABLE `jawaban_pertanyaan`
  ADD CONSTRAINT `jawaban_pertanyaan_ibfk_1` FOREIGN KEY (`id_registration`) REFERENCES `pendaftaran_siswa` (`id_registration`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_pertanyaan_ibfk_2` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan_divisi` (`id_pertanyaan`) ON DELETE CASCADE;

--
-- Constraints for table `jawaban_pertanyaan_opsi`
--
ALTER TABLE `jawaban_pertanyaan_opsi`
  ADD CONSTRAINT `jawaban_pertanyaan_opsi_ibfk_1` FOREIGN KEY (`id_jawaban`) REFERENCES `jawaban_pertanyaan` (`id_jawaban`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_pertanyaan_opsi_ibfk_2` FOREIGN KEY (`id_opsi`) REFERENCES `opsi_pertanyaan` (`id_opsi`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notifications_sender` FOREIGN KEY (`casis_id_notif`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opsi_pertanyaan`
--
ALTER TABLE `opsi_pertanyaan`
  ADD CONSTRAINT `opsi_pertanyaan_ibfk_1` FOREIGN KEY (`id_pertanyaan`) REFERENCES `pertanyaan_divisi` (`id_pertanyaan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pendaftaran_siswa`
--
ALTER TABLE `pendaftaran_siswa`
  ADD CONSTRAINT `pendaftaran_siswa_ibfk_1` FOREIGN KEY (`casis_id_registration`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pendaftaran_siswa_ibfk_2` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pertanyaan_divisi`
--
ALTER TABLE `pertanyaan_divisi`
  ADD CONSTRAINT `pertanyaan_divisi_ibfk_1` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
