ALTER TABLE `dones_silymchs`
	ADD COLUMN `is_return` TINYINT NOT NULL DEFAULT '0' COMMENT '1 - был возврат техники, 0 - нет' AFTER `time_arrival`;