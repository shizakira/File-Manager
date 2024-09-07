-- Adminer 4.8.1 MySQL 9.0.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('directory','file') NOT NULL,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `files` (`id`, `name`, `type`, `parent_id`) VALUES
(50,	'Images',	'directory',	NULL),
(51,	'myfolder',	'directory',	NULL),
(52,	'animals',	'directory',	50),
(53,	'dog.jpg',	'file',	52),
(54,	'cat.jpg',	'file',	52),
(55,	'photos',	'directory',	51),
(57,	'doc.docx',	'file',	55),
(58,	'myphoto.jpeg',	'file',	55);

-- 2024-09-04 18:41:21
