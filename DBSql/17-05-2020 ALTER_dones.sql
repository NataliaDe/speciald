ALTER TABLE `dones`
	ADD COLUMN `is_show_address` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 - выводить адрес в СД, 0 - нет' AFTER `type`;
ALTER TABLE `dones`
	ADD COLUMN `is_show_object` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 - выводить объект в СД, 0 - нет' AFTER `is_show_address`;