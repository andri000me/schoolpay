-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2020 at 08:30 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schoolpay_empty`
--

-- --------------------------------------------------------

--
-- Table structure for table `biodata_sekolah`
--

CREATE TABLE `biodata_sekolah` (
  `id` tinyint(1) NOT NULL,
  `sekolah` varchar(50) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `kode_pos` varchar(5) NOT NULL,
  `tlp` varchar(15) NOT NULL,
  `kelurahan` varchar(30) NOT NULL,
  `kecamatan` varchar(30) NOT NULL,
  `kabupaten` varchar(30) NOT NULL,
  `provinsi` varchar(30) NOT NULL,
  `website` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `kepala_sekolah` varchar(30) NOT NULL,
  `nip_kepala_sekolah` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `biodata_sekolah`
--

INSERT INTO `biodata_sekolah` (`id`, `sekolah`, `nisn`, `alamat`, `kode_pos`, `tlp`, `kelurahan`, `kecamatan`, `kabupaten`, `provinsi`, `website`, `email`, `kepala_sekolah`, `nip_kepala_sekolah`) VALUES
(1, 'SMK Pembaharuan Purworejo', '5236127', 'Jln Kesatrian No 7 Purworejo', '54115', '0275322386', 'Pangen Juru Tengah', 'Purworejo', 'Purworejo', 'Jawa Tengah', 'smkpn-pn2pwr.sch.id', 'zaey_pandawa@yahoo.com', 'H.Suhad,S.Pd,M.MPd', '1234245367');

-- --------------------------------------------------------

--
-- Table structure for table `data_siswa`
--

CREATE TABLE `data_siswa` (
  `nis` varchar(30) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status_kelas` tinyint(2) NOT NULL,
  `program_studi` varchar(5) NOT NULL,
  `kode_kelas` varchar(5) NOT NULL,
  `aktif` enum('Y','T') NOT NULL DEFAULT 'Y',
  `lulus` enum('Y','T') NOT NULL DEFAULT 'T',
  `tahun_lulus` varchar(5) DEFAULT NULL,
  `id_pengguna` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_wali`
--

CREATE TABLE `data_wali` (
  `rowid` int(11) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `id_pengguna` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` tinyint(2) UNSIGNED ZEROFILL NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kelas`) VALUES
(10, 'X'),
(11, 'XI'),
(12, 'XII');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_jenis`
--

CREATE TABLE `keuangan_jenis` (
  `id_jenis` int(4) NOT NULL,
  `pos` int(4) NOT NULL,
  `nama_pembayaran` varchar(50) NOT NULL,
  `tipe` enum('Bulanan','Bebas') NOT NULL,
  `tahun_ajaran` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keuangan_jenis`
--

INSERT INTO `keuangan_jenis` (`id_jenis`, `pos`, `nama_pembayaran`, `tipe`, `tahun_ajaran`) VALUES
(1, 1, 'SPP 2020/2021', 'Bulanan', '20201'),
(2, 2, 'Uang Gedung 2020/2021', 'Bebas', '20201');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_jurnal`
--

CREATE TABLE `keuangan_jurnal` (
  `id_jurnal` int(4) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `debit` varchar(20) NOT NULL,
  `kredit` varchar(20) NOT NULL,
  `petugas` mediumint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_pos`
--

CREATE TABLE `keuangan_pos` (
  `id_pos` int(4) NOT NULL,
  `pos` varchar(20) NOT NULL,
  `keterangan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keuangan_pos`
--

INSERT INTO `keuangan_pos` (`id_pos`, `pos`, `keterangan`) VALUES
(1, 'SPP', 'spp'),
(2, 'Uang Gedung', 'gedung'),
(3, 'Prakerin', 'prakerin');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan_siswa_bulanan`
--

CREATE TABLE `keuangan_siswa_bulanan` (
  `id_siswa_bulanan` int(11) NOT NULL,
  `tahun_ajaran` varchar(5) DEFAULT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `id_jenis` int(4) DEFAULT NULL,
  `b1` varchar(10) DEFAULT NULL,
  `b2` varchar(10) DEFAULT NULL,
  `b3` varchar(10) DEFAULT NULL,
  `b4` varchar(10) DEFAULT NULL,
  `b5` varchar(10) DEFAULT NULL,
  `b6` varchar(10) DEFAULT NULL,
  `b7` varchar(10) DEFAULT NULL,
  `b8` varchar(10) DEFAULT NULL,
  `b9` varchar(10) DEFAULT NULL,
  `b10` varchar(10) DEFAULT NULL,
  `b11` varchar(10) DEFAULT NULL,
  `b12` varchar(10) DEFAULT NULL,
  `id_kelas` tinyint(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `id_program_studi` varchar(5) DEFAULT NULL,
  `kode_kelas` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `midtrans_logs`
--

CREATE TABLE `midtrans_logs` (
  `rowid` int(11) NOT NULL,
  `order_id` varchar(150) DEFAULT NULL,
  `itemid` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `json` text,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `midtrans_onlinepay`
--

CREATE TABLE `midtrans_onlinepay` (
  `nis` varchar(30) NOT NULL,
  `order_id` varchar(150) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `transaction_time` datetime DEFAULT NULL,
  `settlement_time` datetime DEFAULT NULL,
  `transaction_status` varchar(100) DEFAULT NULL,
  `status_message` varchar(100) DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `va_number` varchar(100) DEFAULT NULL,
  `bank` varchar(20) DEFAULT NULL,
  `item` text,
  `gross_amount` int(20) DEFAULT NULL,
  `petugas` varchar(50) NOT NULL,
  `rowid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_siswa_bebas`
--

CREATE TABLE `pembayaran_siswa_bebas` (
  `id_pembayaran_bebas` int(4) NOT NULL,
  `tahun_ajaran` varchar(5) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `id_jenis` int(4) NOT NULL,
  `tanggal` date NOT NULL,
  `cicilan` varchar(11) NOT NULL,
  `petugas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_siswa_bulanan`
--

CREATE TABLE `pembayaran_siswa_bulanan` (
  `id_pembayaran` int(4) NOT NULL,
  `tahun_ajaran` varchar(5) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `id_jenis` int(4) NOT NULL,
  `b1` date NOT NULL,
  `b2` date NOT NULL,
  `b3` date NOT NULL,
  `b4` date NOT NULL,
  `b5` date NOT NULL,
  `b6` date NOT NULL,
  `b7` date NOT NULL,
  `b8` date NOT NULL,
  `b9` date NOT NULL,
  `b10` date NOT NULL,
  `b11` date NOT NULL,
  `b12` date NOT NULL,
  `l1` varchar(10) NOT NULL,
  `l2` varchar(10) NOT NULL,
  `l3` varchar(10) NOT NULL,
  `l4` varchar(10) NOT NULL,
  `l5` varchar(10) NOT NULL,
  `l6` varchar(10) NOT NULL,
  `l7` varchar(10) NOT NULL,
  `l8` varchar(10) NOT NULL,
  `l9` varchar(10) NOT NULL,
  `l10` varchar(10) NOT NULL,
  `l11` varchar(10) NOT NULL,
  `l12` varchar(10) NOT NULL,
  `p1` varchar(50) NOT NULL,
  `p2` varchar(50) NOT NULL,
  `p3` varchar(50) NOT NULL,
  `p4` varchar(50) NOT NULL,
  `p5` varchar(50) NOT NULL,
  `p6` varchar(50) NOT NULL,
  `p7` varchar(50) NOT NULL,
  `p8` varchar(50) NOT NULL,
  `p9` varchar(50) NOT NULL,
  `p10` varchar(50) NOT NULL,
  `p11` varchar(50) NOT NULL,
  `p12` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `status` enum('admin','siswa','wali') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `password`, `nama_lengkap`, `status`) VALUES
('1', 'admin', '775077f295cf6d6f5daba629971c73fe', 'admin 1', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id_program_studi` varchar(5) NOT NULL,
  `program_studi` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id_program_studi`, `program_studi`) VALUES
('MM', 'Multimedia'),
('TGB', 'Teknik Gambar Bagunan'),
('TKJ', 'Teknik Komputer dan Jaringan'),
('TKR', 'Teknik Kendaraan Ringan');

-- --------------------------------------------------------

--
-- Table structure for table `rombel`
--

CREATE TABLE `rombel` (
  `id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rombel`
--

INSERT INTO `rombel` (`id`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `tahun_ajaran` varchar(5) NOT NULL,
  `aktif` enum('Y','T') NOT NULL DEFAULT 'T'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`tahun_ajaran`, `aktif`) VALUES
('20151', 'T'),
('20161', 'T'),
('20171', 'T'),
('20181', 'T'),
('20191', 'T'),
('20201', 'Y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biodata_sekolah`
--
ALTER TABLE `biodata_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_siswa`
--
ALTER TABLE `data_siswa`
  ADD PRIMARY KEY (`nis`);

--
-- Indexes for table `data_wali`
--
ALTER TABLE `data_wali`
  ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `keuangan_jenis`
--
ALTER TABLE `keuangan_jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `keuangan_jurnal`
--
ALTER TABLE `keuangan_jurnal`
  ADD PRIMARY KEY (`id_jurnal`);

--
-- Indexes for table `keuangan_pos`
--
ALTER TABLE `keuangan_pos`
  ADD PRIMARY KEY (`id_pos`);

--
-- Indexes for table `keuangan_siswa_bulanan`
--
ALTER TABLE `keuangan_siswa_bulanan`
  ADD PRIMARY KEY (`id_siswa_bulanan`);

--
-- Indexes for table `midtrans_logs`
--
ALTER TABLE `midtrans_logs`
  ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `midtrans_onlinepay`
--
ALTER TABLE `midtrans_onlinepay`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `rowid` (`rowid`);

--
-- Indexes for table `pembayaran_siswa_bebas`
--
ALTER TABLE `pembayaran_siswa_bebas`
  ADD PRIMARY KEY (`id_pembayaran_bebas`);

--
-- Indexes for table `pembayaran_siswa_bulanan`
--
ALTER TABLE `pembayaran_siswa_bulanan`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id_program_studi`);

--
-- Indexes for table `rombel`
--
ALTER TABLE `rombel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`tahun_ajaran`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_wali`
--
ALTER TABLE `data_wali`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keuangan_jenis`
--
ALTER TABLE `keuangan_jenis`
  MODIFY `id_jenis` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `keuangan_jurnal`
--
ALTER TABLE `keuangan_jurnal`
  MODIFY `id_jurnal` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `keuangan_pos`
--
ALTER TABLE `keuangan_pos`
  MODIFY `id_pos` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `keuangan_siswa_bulanan`
--
ALTER TABLE `keuangan_siswa_bulanan`
  MODIFY `id_siswa_bulanan` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `midtrans_logs`
--
ALTER TABLE `midtrans_logs`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `midtrans_onlinepay`
--
ALTER TABLE `midtrans_onlinepay`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pembayaran_siswa_bebas`
--
ALTER TABLE `pembayaran_siswa_bebas`
  MODIFY `id_pembayaran_bebas` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pembayaran_siswa_bulanan`
--
ALTER TABLE `pembayaran_siswa_bulanan`
  MODIFY `id_pembayaran` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
