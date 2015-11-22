-- Developed by Naxrun - available for everyone
-- Generation Time: Nov 22, 2015 at 02:34 PM
-- Server version: 5.5.45-MariaDB-1~wheezy

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Table structure for table `site_log`
--

CREATE TABLE IF NOT EXISTS `site_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `v_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `v_ref` varchar(1024) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `v_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `v_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_cat` int(10) unsigned NOT NULL,
  `p_url` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `p_cat` (`p_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;