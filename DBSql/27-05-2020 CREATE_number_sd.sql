CREATE TABLE IF NOT EXISTS `number_sd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL DEFAULT '0' COMMENT '=regions. область',
  `first_part` int(11) NOT NULL DEFAULT '0' COMMENT 'первая цифра в номере СД - означает область',
  PRIMARY KEY (`id`),
  KEY `FK_number_sd_regions` (`id_region`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='№ СД. Формат A/B/C.\r\nздесь храним A - означает область';


/*!40000 ALTER TABLE `number_sd` DISABLE KEYS */;
INSERT INTO `number_sd` (`id`, `id_region`, `first_part`) VALUES
	(1, 1, 1),
	(2, 2, 2),
	(3, 4, 3),
	(4, 5, 4),
	(5, 6, 5),
	(6, 7, 6),
	(7, 3, 7),
	(8, 8, 8),
	(9, 9, 8),
	(10, 12, 8),
	(11, 5, 8);


ALTER TABLE `number_sd`
	ADD COLUMN `description` TEXT NOT NULL COMMENT 'пояснения' AFTER `first_part`;

UPDATE `speciald`.`number_sd` SET `description`='РЦУ' WHERE  `id`=11;
UPDATE `speciald`.`number_sd` SET `description`='авиация' WHERE  `id`=10;
UPDATE `speciald`.`number_sd` SET `description`='УГЗ' WHERE  `id`=9;
UPDATE `speciald`.`number_sd` SET `description`='РОСН' WHERE  `id`=8;