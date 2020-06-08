CREATE TABLE `api_source` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`created_by` INT(11) NOT NULL DEFAULT '0' COMMENT 'id user',
	`updated_by` INT(11) NOT NULL DEFAULT '0' COMMENT 'id user',
	`date_insert` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`last_update` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COMMENT='источник финансирования для установки АПИ'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
ALTER TABLE `api_source`
	ADD CONSTRAINT `FK_api_source_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
	ADD CONSTRAINT `FK_api_source_users_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);


ALTER TABLE `api_source`
	CHANGE COLUMN `date_insert` `date_insert` DATETIME NULL DEFAULT NULL AFTER `updated_by`,
	CHANGE COLUMN `last_update` `last_update` DATETIME NULL DEFAULT NULL AFTER `date_insert`;


INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`)
VALUES ('Местный бюджет', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');
INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`)
VALUES ('Внебюджетные средства', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');
INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`) VALUES ('Республиканский бюджет', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');
INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`)
VALUES ('Финансовые организации', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');
INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`)
VALUES ('Банки', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');
INSERT INTO `speciald`.`api_source` (`name`, `created_by`, `updated_by`, `date_insert`, `last_update`)
VALUES ('Фонды', '2', '2', '2020-06-08 17:38:58', '2020-06-08 17:38:59');