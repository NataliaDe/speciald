ALTER TABLE `dones`
	ADD COLUMN `is_delete` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 - СД удалено' AFTER `is_wide_table_trunks`;