--
-- DATABASE STRUCTURE FOR SPORT STARS
--

--
-- Table structure for table `homework_sportstars`
--

CREATE TABLE IF NOT EXISTS `homework_sportstars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'The star name.',
  `age` int(3) NOT NULL DEFAULT 0 COMMENT 'The star age.',
  `sport` varchar(100) NOT NULL COMMENT 'The star sport name.',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`(100)),
  KEY `idx_age` (`age`),
  KEY `idx_sport` (`sport`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=0;
