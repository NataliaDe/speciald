ALTER TABLE `dones`
	ADD COLUMN `is_show_coords` TINYINT(2) NOT NULL DEFAULT '0' COMMENT 'не выводить координаты в СД: 1 - не выводить, 0 - выводить' AFTER `longitude`;