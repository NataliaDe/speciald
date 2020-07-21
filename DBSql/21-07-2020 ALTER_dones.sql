ALTER TABLE `dones`
	ADD COLUMN `is_likv_before_arrival` TINYINT NOT NULL DEFAULT '0' COMMENT 'Ликвидация до прибытия: 1 - да, 0 - нет' AFTER `time_likv`;