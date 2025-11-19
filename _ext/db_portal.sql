-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.6-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_fgi_portal_v3
DROP DATABASE IF EXISTS `db_fgi_portal_v3`;
CREATE DATABASE IF NOT EXISTS `db_fgi_portal_v3` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `db_fgi_portal_v3`;

-- Dumping structure for table db_fgi_portal_v3.table_akun_bank
DROP TABLE IF EXISTS `table_akun_bank`;
CREATE TABLE IF NOT EXISTS `table_akun_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `no_rek` varchar(50) DEFAULT NULL,
  `status` enum('aktif','pending','non-aktif') DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_bab_materi
DROP TABLE IF EXISTS `table_bab_materi`;
CREATE TABLE IF NOT EXISTS `table_bab_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_materi` int(11) DEFAULT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_chat
DROP TABLE IF EXISTS `table_chat`;
CREATE TABLE IF NOT EXISTS `table_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messages` text DEFAULT NULL,
  `status` enum('new','opened') DEFAULT NULL,
  `owner_username` varchar(50) DEFAULT NULL,
  `destination_username` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_comments_rating
DROP TABLE IF EXISTS `table_comments_rating`;
CREATE TABLE IF NOT EXISTS `table_comments_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materi` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `ratings` int(11) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_customer_services
DROP TABLE IF EXISTS `table_customer_services`;
CREATE TABLE IF NOT EXISTS `table_customer_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `whatsapp` varchar(50) DEFAULT NULL,
  `status` enum('enable','disabled') DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_daily_notes
DROP TABLE IF EXISTS `table_daily_notes`;
CREATE TABLE IF NOT EXISTS `table_daily_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_detail_materi
DROP TABLE IF EXISTS `table_detail_materi`;
CREATE TABLE IF NOT EXISTS `table_detail_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materi` int(11) DEFAULT NULL,
  `judul` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_group_diskusi
DROP TABLE IF EXISTS `table_group_diskusi`;
CREATE TABLE IF NOT EXISTS `table_group_diskusi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `jenis` enum('wa','telegram') DEFAULT 'wa',
  `url` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_history_saldo
DROP TABLE IF EXISTS `table_history_saldo`;
CREATE TABLE IF NOT EXISTS `table_history_saldo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `saldo_sebelum` int(11) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `saldo_setelah` int(11) DEFAULT NULL,
  `jenis` enum('isi saldo','daftar kursus','bonus','cetak sertifikat','dll') DEFAULT NULL,
  `status` enum('pending','approved','cancelled') DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_info_afiliasi
DROP TABLE IF EXISTS `table_info_afiliasi`;
CREATE TABLE IF NOT EXISTS `table_info_afiliasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(50) NOT NULL DEFAULT '',
  `berita` text NOT NULL,
  `status` enum('enable','disabled') NOT NULL DEFAULT 'disabled',
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_kategori_materi
DROP TABLE IF EXISTS `table_kategori_materi`;
CREATE TABLE IF NOT EXISTS `table_kategori_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(200) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_materi
DROP TABLE IF EXISTS `table_materi`;
CREATE TABLE IF NOT EXISTS `table_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(50) DEFAULT 'question.png',
  `judul` varchar(200) DEFAULT NULL,
  `kategori` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `url` varchar(75) DEFAULT NULL,
  `biaya_belajar_sendiri` int(11) DEFAULT NULL,
  `biaya_pokok` int(11) DEFAULT NULL,
  `biaya_kasus_custom` int(11) DEFAULT NULL,
  `rilis_sertifikat` enum('yes','no') DEFAULT 'no',
  `paket_belajar_sendiri` enum('yes','no') DEFAULT 'no',
  `paket_bimbingan` enum('yes','no') DEFAULT 'no',
  `paket_kasus_custom` enum('yes','no') DEFAULT 'no',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_member_afiliasi
DROP TABLE IF EXISTS `table_member_afiliasi`;
CREATE TABLE IF NOT EXISTS `table_member_afiliasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_program_afiliasi` int(11) DEFAULT NULL,
  `user_count_total` int(11) NOT NULL DEFAULT 0,
  `user_count_confirmed_total` int(11) NOT NULL DEFAULT 0,
  `user_cash_paid` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_pembahasan_materi
DROP TABLE IF EXISTS `table_pembahasan_materi`;
CREATE TABLE IF NOT EXISTS `table_pembahasan_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bab` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `ordering_index` int(11) DEFAULT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_perangkat_tautan
DROP TABLE IF EXISTS `table_perangkat_tautan`;
CREATE TABLE IF NOT EXISTS `table_perangkat_tautan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materi` int(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_program_afiliasi
DROP TABLE IF EXISTS `table_program_afiliasi`;
CREATE TABLE IF NOT EXISTS `table_program_afiliasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `total_member` int(11) DEFAULT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'question.png',
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_progress_materi
DROP TABLE IF EXISTS `table_progress_materi`;
CREATE TABLE IF NOT EXISTS `table_progress_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materi` int(11) DEFAULT NULL,
  `id_detail_materi` int(11) DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `username` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_student_materi
DROP TABLE IF EXISTS `table_student_materi`;
CREATE TABLE IF NOT EXISTS `table_student_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_materi` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `status` enum('pending','in progress','completed','error','delete request') DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_subscriptions
DROP TABLE IF EXISTS `table_subscriptions`;
CREATE TABLE IF NOT EXISTS `table_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) DEFAULT NULL,
  `students_limit` enum('unlimited','4 students','none') DEFAULT NULL,
  `fitur_sertifikat` tinyint(1) DEFAULT NULL,
  `fitur_diskusi_wa` tinyint(1) DEFAULT NULL,
  `url_diskusi_wa` varchar(200) DEFAULT NULL,
  `fitur_loker` tinyint(1) DEFAULT NULL,
  `fitur_materi_gratis` tinyint(1) DEFAULT NULL,
  `fitur_aset_digital` tinyint(1) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_support_tickets
DROP TABLE IF EXISTS `table_support_tickets`;
CREATE TABLE IF NOT EXISTS `table_support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_number` varchar(7) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `customer_name` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `descriptions` text DEFAULT NULL,
  `status` enum('completed','pending','rejected') DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_system_notification
DROP TABLE IF EXISTS `table_system_notification`;
CREATE TABLE IF NOT EXISTS `table_system_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messages` text DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `status` enum('new','opened') DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table db_fgi_portal_v3.table_users
DROP TABLE IF EXISTS `table_users`;
CREATE TABLE IF NOT EXISTS `table_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT 'male',
  `whatsapp` varchar(50) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `usertype` enum('peserta','instruktur','admin') DEFAULT NULL,
  `propic` varchar(50) NOT NULL DEFAULT 'empty.png',
  `balance` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
