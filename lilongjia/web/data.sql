Drop database if exists aolong;
Create database aolong;
DROP TABLE IF EXISTS `aolong`.`message`;
CREATE TABLE `aolong`.`message` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `gsname` VARCHAR(200) NOT NULL DEFAULT '',
  `username` VARCHAR(45) NOT NULL DEFAULT '',
  `tel` VARCHAR(45) NOT NULL DEFAULT '',
  `email` VARCHAR(45) NOT NULL DEFAULT '',
  `qq` VARCHAR(45) NOT NULL DEFAULT '',
  `msn` VARCHAR(45) NOT NULL DEFAULT '',
  `subject` VARCHAR(200) NOT NULL DEFAULT '',
  `content` TEXT,
  `time` DATETIME NOT NULL DEFAULT 0,
  PRIMARY KEY(`id`),
  INDEX `time`(`time`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `aolong`.`news`;
CREATE TABLE  `aolong`.`news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(45) NOT NULL DEFAULT 'zh',
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `subject` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `aolong`.`config`;
CREATE TABLE  `aolong`.`config` (
  `variable` varchar(50) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
