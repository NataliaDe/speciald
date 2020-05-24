ALTER TABLE `dones`
	ADD COLUMN `is_show_prevention` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 - выводить профилактику в СД 0 - нет' AFTER `is_show_object`;


ALTER TABLE `dones`
	ADD COLUMN `id_firereason` INT NOT NULL DEFAULT '0' COMMENT 'Причина пожара = journal.firereason.id' AFTER `firereason_rig`;