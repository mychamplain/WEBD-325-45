--
-- DATABASE STRUCTURE FOR GENERIC CMS (Adapted from Joomla!)
--                -- subject to change --
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table structure for table `llewellyn_menu`
--

CREATE TABLE IF NOT EXISTS `llewellyn_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `published` tinyint NOT NULL DEFAULT 0 COMMENT 'The published state of the menu link.',
  `parent_id` int unsigned NOT NULL DEFAULT 1 COMMENT 'The parent menu item in the menu tree.',
  `level` int unsigned NOT NULL DEFAULT 0 COMMENT 'The relative level in the tree.',
  `item_id` int unsigned NOT NULL DEFAULT 0 COMMENT 'FK to llewellyn_item.id',
  `checked_out` int unsigned COMMENT 'FK to llewellyn_users.id',
  `checked_out_time` datetime COMMENT 'The time the menu item was checked out.',
  `params` text NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  `rgt` int NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  `home` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'Indicates if this menu item is the home or default page.',
  `publish_up` datetime,
  `publish_down` datetime,
  PRIMARY KEY (`id`),
  KEY `idx_item` (`item_id`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`(100)),
  KEY `idx_path` (`path`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=102;

--
-- Table structure for table `llewellyn_session`
--

CREATE TABLE IF NOT EXISTS `llewellyn_session` (
  `session_id` varbinary(192) NOT NULL,
  `guest` tinyint unsigned DEFAULT 1,
  `time` int NOT NULL DEFAULT 0,
  `data` mediumtext,
  `userid` int DEFAULT 0,
  `username` varchar(150) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `userid` (`userid`),
  KEY `time` (`time`),
  KEY `guest` (`guest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `llewellyn_users`
--

CREATE TABLE IF NOT EXISTS `llewellyn_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(400) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `block` tinyint NOT NULL DEFAULT 0,
  `sendEmail` tinyint DEFAULT 0,
  `registerDate` datetime NOT NULL,
  `lastvisitDate` datetime,
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `lastResetTime` datetime COMMENT 'Date of last password reset',
  `resetCount` int NOT NULL DEFAULT 0 COMMENT 'Count of password resets since lastResetTime',
  `requireReset` tinyint NOT NULL DEFAULT 0 COMMENT 'Require user to reset password on next login',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`(100)),
  KEY `idx_block` (`block`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `llewellyn_usergroups`
--

CREATE TABLE IF NOT EXISTS `llewellyn_usergroups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int unsigned NOT NULL DEFAULT 0 COMMENT 'Adjacency List Reference Id',
  `lft` int NOT NULL DEFAULT 0 COMMENT 'Nested set lft.',
  `rgt` int NOT NULL DEFAULT 0 COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_parent_title_lookup` (`parent_id`,`title`),
  KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `llewellyn_user_usergroup_map`
--

CREATE TABLE IF NOT EXISTS `llewellyn_user_usergroup_map` (
  `user_id` int unsigned NOT NULL DEFAULT 0 COMMENT 'Foreign Key to llewellyn_users.id',
  `group_id` int unsigned NOT NULL DEFAULT 0 COMMENT 'Foreign Key to llewellyn_usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `llewellyn_item`
--

CREATE TABLE IF NOT EXISTS `llewellyn_item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `created_by` int unsigned NOT NULL DEFAULT 0,
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL,
  `modified_by` int unsigned NOT NULL DEFAULT 0,
  `checked_out` int unsigned,
  `checked_out_time` datetime NULL DEFAULT NULL,
  `publish_up` datetime NULL DEFAULT NULL,
  `publish_down` datetime NULL DEFAULT NULL,
  `version` int unsigned NOT NULL DEFAULT 1,
  `ordering` int NOT NULL DEFAULT 0,
  `metakey` text,
  `metadesc` text NOT NULL,
  `hits` int unsigned NOT NULL DEFAULT 0,
  `metadata` text NOT NULL,
  `featured` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'Set if article is featured.',
  PRIMARY KEY (`id`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_alias` (`alias`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


