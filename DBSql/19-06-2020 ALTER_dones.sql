ALTER TABLE `dones`
	ADD COLUMN `is_water_source` TINYINT NOT NULL DEFAULT '0' COMMENT '1 - использовался водоисточник, 0 - нет' AFTER `detail_inf`;