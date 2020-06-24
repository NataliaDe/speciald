ALTER TABLE `dones`
	ADD COLUMN `is_owner_multi` TINYINT NOT NULL DEFAULT '0' COMMENT 'Собственник: 1 - несколько собственников, 0 - нет' AFTER `is_show_owner`;

ALTER TABLE `dones`
	ADD COLUMN `owner_multi_descr` TEXT NOT NULL COMMENT 'Собственник: пояснение, если стоит отметка "несколько собственников"' AFTER `is_owner_multi`;