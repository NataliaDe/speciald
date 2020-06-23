-- Дамп структуры для таблица speciald.map_center_regions
CREATE TABLE IF NOT EXISTS `map_center_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_region` int(11) NOT NULL DEFAULT '0' COMMENT 'область',
  `lat` varchar(50) NOT NULL DEFAULT '0' COMMENT 'широта',
  `lon` varchar(50) NOT NULL DEFAULT '0' COMMENT 'долгота',
  PRIMARY KEY (`id`),
  KEY `FK_map_center_regions` (`id_region`),
  CONSTRAINT `FK_map_center_regions` FOREIGN KEY (`id_region`) REFERENCES `regions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='координаты для центра карты по каждой области';

-- Дамп данных таблицы speciald.map_center_regions: ~0 rows (приблизительно)
DELETE FROM `map_center_regions`;
/*!40000 ALTER TABLE `map_center_regions` DISABLE KEYS */;
INSERT INTO `map_center_regions` (`id`, `id_region`, `lat`, `lon`) VALUES
	(1, 3, '53.891474', '27.551287'),
	(2, 1, '52.098837', '23.681109'),
	(3, 2, '55.194536', '30.184793'),
	(4, 4, '52.430630', '30.992175'),
	(5, 7, '53.925365', '30.337480'),
	(6, 5, '53.686232', '23.849272'),
	(7, 6, '53.891474', '27.551287');
/*!40000 ALTER TABLE `map_center_regions` ENABLE KEYS */;