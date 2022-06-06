/*
SQLyog Enterprise - MySQL GUI v6.1
MySQL - 8.0.29-0ubuntu0.20.04.3 : Database - microblog
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `tbl_comments` */

DROP TABLE IF EXISTS `tbl_comments`;

CREATE TABLE `tbl_comments` (
  `comment_id` int unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `comment` longtext,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `tbl_otp_requests` */

DROP TABLE IF EXISTS `tbl_otp_requests`;

CREATE TABLE `tbl_otp_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mobile_no` bigint DEFAULT NULL,
  `otp` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `tbl_posts` */

DROP TABLE IF EXISTS `tbl_posts`;

CREATE TABLE `tbl_posts` (
  `post_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `tbl_users` */

DROP TABLE IF EXISTS `tbl_users`;

CREATE TABLE `tbl_users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `mobile_no` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  PRIMARY KEY (`user_id`),
  KEY `mobile_no` (`mobile_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Table structure for table `tbl_votes` */

DROP TABLE IF EXISTS `tbl_votes`;

CREATE TABLE `tbl_votes` (
  `vote_id` int unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `vote` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`vote_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
