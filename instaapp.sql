/*
SQLyog Ultimate v12.4.3 (64 bit)
MySQL - 5.5.36 : Database - instaapp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`instaapp` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `instaapp`;

/*Table structure for table `post_tl` */

DROP TABLE IF EXISTS `post_tl`;

CREATE TABLE `post_tl` (
  `post_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `comment` text,
  `user_id` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `post_tl` */

insert  into `post_tl`(`post_id`,`sequence`,`comment`,`user_id`,`date_created`) values 
(2,1,'test comment',1,'2021-08-14 07:38:54'),
(4,1,'comment sdlc',1,'2021-08-14 07:39:27'),
(4,2,'comment 2 sdlc',1,'2021-08-14 07:40:11'),
(2,2,'test comment 2 jimny',1,'2021-08-14 07:40:41'),
(3,1,'comment rack',2,'2021-08-14 07:53:56'),
(2,3,'test comment testing jimny',2,'2021-08-14 07:59:03'),
(4,3,'comment testing sdlc',2,'2021-08-14 08:00:02'),
(3,2,'test comment rack',1,'2021-08-14 08:01:14'),
(5,1,'comment jono',3,'2021-08-14 08:24:13'),
(4,4,'comment jono',3,'2021-08-14 08:26:30'),
(6,1,'comment yanto',4,'2021-08-14 08:34:55'),
(2,4,'comment yanto',4,'2021-08-14 08:35:49');

/*Table structure for table `post_tm` */

DROP TABLE IF EXISTS `post_tm`;

CREATE TABLE `post_tm` (
  `post_id` int(11) NOT NULL,
  `caption` text,
  `upload_file` varchar(150) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status_id` varchar(4) DEFAULT 'ST01',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `post_tm` */

insert  into `post_tm`(`post_id`,`caption`,`upload_file`,`user_id`,`status_id`,`date_created`) values 
(2,'suzuki jimny jb74','2021/08/13/152235770.jpg',1,'ST01','2021-08-13 20:22:35'),
(3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.','2021/08/13/163012898.jpg',2,'ST01','2021-08-13 21:30:12'),
(4,'Tahapan SDLC','2021/08/14/011640223.jpg',1,'ST01','2021-08-14 06:16:40'),
(5,'Postingan pertamaku','2021/08/14/032258463.png',3,'ST01','2021-08-14 08:22:58'),
(6,'postingan pertama yanto','2021/08/14/033338937.jpg',4,'ST01','2021-08-14 08:33:38');

/*Table structure for table `status_tr` */

DROP TABLE IF EXISTS `status_tr`;

CREATE TABLE `status_tr` (
  `status_id` varchar(4) NOT NULL COMMENT 'ID Status',
  `name` varchar(150) NOT NULL COMMENT 'Nama Status',
  `code` varchar(10) DEFAULT NULL COMMENT 'Kode Status',
  `counter` smallint(6) DEFAULT NULL COMMENT 'Nomor Urut',
  `date_update` datetime DEFAULT NULL COMMENT 'Waktu Update Nomor Urut',
  `parent` varchar(4) DEFAULT NULL COMMENT 'ID Atasan',
  `status` varchar(4) NOT NULL DEFAULT 'ST01' COMMENT 'ID Aktifasi Status',
  PRIMARY KEY (`status_id`),
  KEY `FK_status_tr_01` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `status_tr` */

insert  into `status_tr`(`status_id`,`name`,`code`,`counter`,`date_update`,`parent`,`status`) values 
('DE01','Aktif',NULL,NULL,NULL,NULL,'ST01'),
('DE02','Tidak Aktif',NULL,NULL,NULL,NULL,'ST01'),
('DE03','Aktif - Dropdown',NULL,NULL,NULL,NULL,'ST01'),
('RL01','Administrator',NULL,NULL,NULL,NULL,'ST01'),
('SI01','Baru',NULL,NULL,NULL,NULL,'ST01'),
('SI02','Sukses',NULL,NULL,NULL,NULL,'ST01'),
('SI03','Gagal',NULL,NULL,NULL,NULL,'ST01'),
('SI04','Selesai',NULL,NULL,NULL,NULL,'ST01'),
('ST00','Belum Aktif',NULL,NULL,NULL,NULL,'ST01'),
('ST01','Aktif',NULL,NULL,NULL,NULL,'ST01'),
('ST02','Tidak Aktif',NULL,NULL,NULL,NULL,'ST01'),
('ST03','Tidak Berlaku',NULL,NULL,NULL,NULL,'ST01');

/*Table structure for table `user_tm` */

DROP TABLE IF EXISTS `user_tm`;

CREATE TABLE `user_tm` (
  `user_id` int(11) NOT NULL COMMENT 'ID User',
  `login` varchar(100) NOT NULL COMMENT 'User Login',
  `password` varchar(32) NOT NULL COMMENT 'Password',
  `name` varbinary(150) NOT NULL COMMENT 'Nama User',
  `email` varchar(150) NOT NULL COMMENT 'Alamat Email',
  `phone` varchar(30) DEFAULT NULL COMMENT 'Nomor Telepon',
  `status_id` varchar(4) NOT NULL DEFAULT 'ST01' COMMENT 'ID Status User',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu User Dibuat',
  `date_login` datetime DEFAULT NULL COMMENT 'Waktu User Login',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `UN_user_tm` (`login`),
  KEY `FK_user_tm_04` (`status_id`),
  CONSTRAINT `FK_user_tm_04` FOREIGN KEY (`status_id`) REFERENCES `status_tr` (`status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_tm` */

insert  into `user_tm`(`user_id`,`login`,`password`,`name`,`email`,`phone`,`status_id`,`date_created`,`date_login`) values 
(1,'test','123','afif khoirul','test@mail.com','082330660295','ST01','2021-08-13 15:12:42',NULL),
(2,'testing','123','Testing','mail@mail.com','324235','ST01','2021-08-13 21:25:28',NULL),
(3,'joko','123','joko sujono','joko@mail.com','0283894','ST01','2021-08-14 08:21:06',NULL),
(4,'yanto','123','supriyanto','mail@mail.com','08767565','ST01','2021-08-14 08:32:01',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
