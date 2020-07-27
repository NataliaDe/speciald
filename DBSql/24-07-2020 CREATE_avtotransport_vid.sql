CREATE TABLE IF NOT EXISTS `avtotransport_vid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Классификатор "Вид автотранспорта" для блока "Объект", если выбран вид объекта "Автотранспорт".';

-- Дамп данных таблицы speciald.avtotransport_vid: ~0 rows (приблизительно)
DELETE FROM `avtotransport_vid`;
/*!40000 ALTER TABLE `avtotransport_vid` DISABLE KEYS */;
INSERT INTO `avtotransport_vid` (`id`, `name`) VALUES
	(1, 'грузовой'),
	(2, 'легковой');