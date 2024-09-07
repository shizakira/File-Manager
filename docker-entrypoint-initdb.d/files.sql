CREATE TABLE `files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('directory','file') NOT NULL,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
);

INSERT INTO `files` (`id`, `name`, `type`, `parent_id`) VALUES
(50, 'Images', 'directory', NULL),
(51, 'myfolder', 'directory', NULL),
(52, 'animals', 'directory', 50),
(53, 'dog.jpg', 'file', 52),
(54, 'cat.jpg', 'file', 52),
(55, 'photos', 'directory', 51),
(57, 'doc.docx', 'file', 55),
(58, 'myphoto.jpeg', 'file', 55);
