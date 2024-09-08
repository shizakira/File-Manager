CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('directory','file') NOT NULL,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `files` (`id`) ON DELETE CASCADE
);

INSERT INTO `files` (`id`, `name`, `type`, `parent_id`) VALUES
(29,	'images',	'directory',	NULL),
(30,	'animals',	'directory',	29),
(31,	'cat.jpg',	'file',	30),
(32,	'dog.jpg',	'file',	30),
(33,	'myfolder',	'directory',	NULL),
(34,	'photos',	'directory',	33),
(35,	'myphoto.jpeg',	'file',	34),
(36,	'doc.docx',	'file',	33);
