-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 03 avr. 2023 à 06:36
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Administrateur', 'admin@gmail.com', 'admin', '$2y$10$8fuiqVfriZJCAdbIUcIRRem7P0JcCGOwmeclFoBWkG2CjpM/lWy.i', '2023-03-30 09:58:59');

-- --------------------------------------------------------

--
-- Structure de la table `tblauthors`
--

DROP TABLE IF EXISTS `tblauthors`;
CREATE TABLE IF NOT EXISTS `tblauthors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `Status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblauthors`
--

INSERT INTO `tblauthors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`, `Status`) VALUES
(1, 'Guillaume Musso', '2017-07-08 12:49:09', '2023-03-21 14:16:14', 1),
(2, 'Michel Bussi', '2017-07-08 14:30:23', '2023-03-21 14:17:46', 1),
(3, 'Marc Levy', '2017-07-08 14:35:08', '2023-03-21 14:18:05', 1),
(4, 'Françoise Bourdin', '2017-07-08 14:35:21', '2023-03-21 14:18:18', 1),
(5, 'Gilles Legardinier', '2017-07-08 14:35:36', '2023-03-21 14:18:38', 1),
(9, 'Agnès Martin', '2017-07-08 15:22:03', '2023-03-21 14:19:13', 1),
(10, 'Annie Ernaux', '2021-06-23 12:39:10', '2023-03-21 14:19:30', 1),
(12, 'Tolstoï', '2023-03-21 10:44:57', '2023-03-21 12:57:49', 1),
(13, 'Jean-Christophe Grangé', '2023-03-21 10:45:41', '2023-03-29 06:38:38', 1),
(14, 'Maurice Leblanc', '2023-03-21 11:02:18', '2023-03-21 15:23:59', 1),
(15, 'Anonymous', '2023-03-21 14:31:42', NULL, 1),
(16, 'Louis Pergaud', '2023-03-22 08:05:15', NULL, 1),
(18, 'Tolstwa', '2023-03-27 14:00:07', NULL, 1),
(78, 'Le roi du monde', '2023-03-29 14:10:16', NULL, 1),
(79, 'Bernard Werber', '2023-03-30 08:33:59', '2023-03-30 08:38:20', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tblbooks`
--

DROP TABLE IF EXISTS `tblbooks`;
CREATE TABLE IF NOT EXISTS `tblbooks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int DEFAULT NULL,
  `AuthorId` int DEFAULT NULL,
  `ISBNNumber` int DEFAULT NULL,
  `BookPrice` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblbooks`
--

INSERT INTO `tblbooks` (`id`, `BookName`, `CatId`, `AuthorId`, `ISBNNumber`, `BookPrice`, `RegDate`, `UpdationDate`) VALUES
(1, 'La jeune fille et la nuit', 4, 1, 222333, 21, '2017-07-08 20:04:55', '2021-08-06 15:37:08'),
(3, 'Quelqu\'un de bien', 4, 4, 111123, 6, '2017-07-08 20:17:31', '2021-07-26 09:12:22'),
(14, 'L\'aiguille creuse', 48, 14, 2147483647, 19, '2023-03-22 12:06:16', NULL),
(16, 'L\'ordinateur quantique', 6, 15, 666, 20, '2023-03-22 12:08:17', '2023-03-22 14:13:24'),
(17, 'L\'ordinateur quantique', 5, 15, 666, 20, '2023-03-29 10:05:45', NULL),
(18, 'L\'ordinateur quantique', 5, 15, 666, 20, '2023-03-29 10:06:09', NULL),
(19, 'Miserere', 48, 13, 123456, 25, '2023-03-29 14:11:11', NULL),
(20, 'Les fourmis', 48, 79, 798465, 25, '2023-03-30 08:39:35', '2023-03-30 08:40:36');

-- --------------------------------------------------------

--
-- Structure de la table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
CREATE TABLE IF NOT EXISTS `tblcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(4, 'Fiction', 1, '2023-03-21 20:22:35', '2023-03-24 14:47:56'),
(5, 'Technologie', 1, '2017-07-04 18:35:39', '2023-03-21 10:09:37'),
(6, 'Science', 1, '2017-07-04 18:35:55', '2021-08-06 15:31:10'),
(7, 'Management', 1, '2017-07-04 18:36:16', '2021-06-23 12:45:41'),
(40, 'Science du coucou', 1, '2023-03-20 15:28:06', '2023-03-24 14:48:15'),
(45, 'Fantasy', 1, '2023-03-21 09:09:18', '2023-03-21 10:09:11'),
(47, 'Techno', 1, '2023-03-21 09:09:22', '2023-03-21 10:05:06'),
(48, 'Roman', 1, '2023-03-21 15:25:10', '2023-03-24 14:48:26'),
(49, 'Anticipation', 1, '2023-03-29 14:10:05', '2023-03-29 12:51:26'),
(50, 'Science du coucou', 1, '2023-03-30 06:44:51', '0000-00-00 00:00:00'),
(51, 'Historique', 1, '2023-03-30 06:52:04', '2023-03-30 06:04:42'),
(52, 'Historique', 1, '2023-03-30 08:32:52', '2023-03-30 06:33:03');

-- --------------------------------------------------------

--
-- Structure de la table `tblissuedbookdetails`
--

DROP TABLE IF EXISTS `tblissuedbookdetails`;
CREATE TABLE IF NOT EXISTS `tblissuedbookdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookId` int DEFAULT NULL,
  `ReaderID` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ReturnStatus` int DEFAULT NULL,
  `fine` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblissuedbookdetails`
--

INSERT INTO `tblissuedbookdetails` (`id`, `BookId`, `ReaderID`, `IssuesDate`, `ReturnDate`, `ReturnStatus`, `fine`) VALUES
(1, 1, 'SID002', '2017-07-15 06:09:47', '2023-03-24 09:55:49', 1, 0),
(2, 1, 'SID002', '2017-07-15 06:12:27', '2017-07-15 11:15:23', 1, 5),
(3, 3, 'SID002', '2017-07-15 06:13:40', '2023-03-24 09:56:28', 1, NULL),
(4, 3, 'SID002', '2017-07-15 06:23:23', '2017-07-15 11:22:29', 1, 2),
(5, 1, 'SID009', '2017-07-15 10:59:26', NULL, 0, NULL),
(6, 3, 'SID011', '2017-07-15 18:02:55', NULL, 0, NULL),
(7, 1, 'SID011', '2021-07-16 13:59:23', NULL, 0, NULL),
(8, 1, 'SID010', '2021-07-20 08:41:34', NULL, 0, NULL),
(9, 3, 'SID012', '2021-07-20 08:44:53', NULL, 0, NULL),
(10, 1, 'SID012', '2021-07-20 08:47:07', NULL, 0, NULL),
(11, 222333, 'SID009', '2021-07-20 08:51:15', NULL, 0, NULL),
(12, 222333, 'SID009', '2021-07-20 09:53:27', NULL, 0, NULL),
(13, 222333, 'SID014', '2021-07-21 14:49:46', '2021-07-21 22:00:00', 1, NULL),
(14, 222333, 'SID017', '2021-07-29 14:14:15', '2021-08-04 22:00:00', 1, NULL),
(15, 222333, 'SID022', '2021-07-30 07:40:06', NULL, 0, NULL),
(16, 222333, 'SID001', '2021-08-06 15:20:20', '2023-03-24 09:42:08', 0, NULL),
(17, 222333, 'SID021', '2021-08-06 15:22:22', NULL, 0, NULL),
(41, 16, 'SID027', '2023-03-24 09:49:09', '2023-03-24 09:57:10', 1, NULL),
(53, 1, 'SID032', '2023-03-24 10:06:19', '2023-03-24 09:56:49', 1, NULL),
(54, 14, 'SID032', '2023-03-24 10:06:48', NULL, 0, NULL),
(55, 16, 'SID032', '2023-03-24 10:08:03', '2023-03-30 06:10:55', 0, NULL),
(56, 3, 'SID032', '2023-03-24 10:08:22', '2023-03-29 12:49:41', 1, NULL),
(57, 3, 'SID027', '2023-03-24 10:10:21', '2023-03-30 06:30:20', 0, NULL),
(58, 14, 'SID027', '2023-03-24 10:10:29', NULL, 0, NULL),
(59, 1, 'SID027', '2023-03-24 10:10:33', NULL, 0, NULL),
(102, 3, 'SID027', '2023-03-29 14:45:43', '2023-03-30 05:17:31', 1, NULL),
(103, 20, 'SID027', '2023-03-30 08:41:10', '2023-03-30 06:41:40', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblreaders`
--

DROP TABLE IF EXISTS `tblreaders`;
CREATE TABLE IF NOT EXISTS `tblreaders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ReaderId` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tblreaders`
--

INSERT INTO `tblreaders` (`id`, `ReaderId`, `FullName`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdateDate`) VALUES
(11, 'SID002', 'test', 'test@gmail.com', '06060606', '$2y$10$mHHZa9tagaNOi3pFUXZEd.uoLxfbg1jWOAUp8xyh5dnn8KeRLKJXC', 1, '2023-02-24 08:29:33', '2023-03-30 12:26:10'),
(15, 'SID027', 'alex', 'alex@gmail.com', '0707070707', '$2y$10$mHHZa9tagaNOi3pFUXZEd.uoLxfbg1jWOAUp8xyh5dnn8KeRLKJXC', 1, '2023-03-16 14:26:48', '2023-03-30 12:26:10'),
(16, 'SID032', 'Wikiny', 'wikiny@gmail.com', '0707070707', '$2y$10$mHHZa9tagaNOi3pFUXZEd.uoLxfbg1jWOAUp8xyh5dnn8KeRLKJXC', 0, '2023-03-16 14:55:39', '2023-03-30 12:26:10'),
(17, 'SID033', 'zaeidubgzei', 'a@a', '05169168461', '$2y$10$mHHZa9tagaNOi3pFUXZEd.uoLxfbg1jWOAUp8xyh5dnn8KeRLKJXC', 0, '2023-03-23 11:31:33', '2023-03-30 12:26:10'),
(18, 'SID034', 'hgvgh', 'a@g', '61516', '$2y$10$mHHZa9tagaNOi3pFUXZEd.uoLxfbg1jWOAUp8xyh5dnn8KeRLKJXC', 2, '2023-03-23 11:32:41', '2023-03-30 12:26:10'),
(19, 'SID033', 'coucou', 'coucou@gmail.com', '0808080808', '$2y$10$bFiwIxqJ5rlFcjFxyWvbvOwszkqZpQ6CdccVH29lx1RiPQZWMOXR2', 1, '2023-03-30 12:22:59', '2023-03-30 12:26:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
