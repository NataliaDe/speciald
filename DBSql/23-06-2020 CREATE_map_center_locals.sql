CREATE TABLE IF NOT EXISTS `map_center_locals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_local` int(11) NOT NULL DEFAULT '0' COMMENT 'район',
  `lat` varchar(50) NOT NULL DEFAULT '0' COMMENT 'широта',
  `lon` varchar(50) NOT NULL DEFAULT '0' COMMENT 'долгота',
  PRIMARY KEY (`id`),
  KEY `FK_map_center_locals` (`id_local`),
  CONSTRAINT `FK_map_center_locals` FOREIGN KEY (`id_local`) REFERENCES `locals` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='координаты для центра карты';

-- Дамп данных таблицы speciald.map_center_locals: ~3 rows (приблизительно)
DELETE FROM `map_center_locals`;
/*!40000 ALTER TABLE `map_center_locals` DISABLE KEYS */;
INSERT INTO `map_center_locals` (`id`, `id_local`, `lat`, `lon`) VALUES
	(1, 1, '53.149261', '26.020082'),
	(2, 7, '53.052115', '29.252133'),
	(3, 123, '53.900000', '27.566670');
/*!40000 ALTER TABLE `map_center_locals` ENABLE KEYS */;