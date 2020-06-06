ALTER TABLE `dones`
	CHANGE COLUMN `people_is_uhet` `owner_is_uhet` TINYINT(4) NOT NULL DEFAULT '0' COMMENT ' Данные по собственнику: Состоит на учете профилактики :  1 - да, 0- нет' AFTER `people_status`;




ALTER TABLE `dones`
	ADD COLUMN `id_owner_category` INT NOT NULL DEFAULT 0 COMMENT 'Данные по собственнику: категория. = journal.rig.id_owner_category' AFTER `id_face_belong`,
	ADD COLUMN `owner_fio` TEXT NOT NULL COMMENT 'Данные по собственнику: ФИО = journal.rig.owner_fio' AFTER `id_owner_category`,
	ADD COLUMN `owner_year_birthday` INT NOT NULL COMMENT 'Данные по собственнику: год рождения = journal.rig.owner_year_birthday' AFTER `owner_fio`,
	ADD COLUMN `owner_address` TEXT NOT NULL COMMENT 'Данные по собственнику: место жительства= journal.rig.owner_address' AFTER `owner_year_birthday`,
	ADD COLUMN `owner_position` TEXT NOT NULL COMMENT 'Данные по собственнику: должность= journal.rig.owner_position' AFTER `owner_address`,
	ADD COLUMN `owner_job` TEXT NOT NULL COMMENT 'Данные по собственнику: место работы= journal.rig.owner_job' AFTER `owner_position`,
	ADD COLUMN `owner_character` TEXT NOT NULL COMMENT 'Данные по собственнику: характеристика' AFTER `owner_job`,
	CHANGE COLUMN `owner_is_uhet` `owner_is_uhet` TINYINT(4) NOT NULL DEFAULT '0' COMMENT ' Данные по собственнику: Состоит на учете профилактики :  1 - да, 0- нет' AFTER `owner_character`,
	ADD COLUMN `owner_live_together` INT NOT NULL DEFAULT 0 COMMENT 'Данные по собственнику: совместно проживает человек' AFTER `owner_is_uhet`;



ALTER TABLE `dones`
	ADD COLUMN `law_face_office_belong` INT(11) NOT NULL DEFAULT '0' COMMENT 'Данные по собственнику (юр.лицо): вед.принадлежность = journal.rig.office_belong_id' AFTER `owner_live_together`,
	ADD COLUMN `law_face_name_owner` TEXT NOT NULL COMMENT 'Данные по собственнику (юр.лицо): наименование собственника ' AFTER `law_face_office_belong`;

CREATE TABLE `dones_live_together` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_dones` INT(11) NOT NULL COMMENT 'ID спец. донесения',
	`fio` TEXT(65535) NOT NULL COMMENT 'ФИО совместно проживающего человека)' COLLATE 'utf8_general_ci',
	`year_birthday` INT(11) NOT NULL  COMMENT 'год рождения совместно проживающего человека',
	`note` text   COMMENT 'примечание',
	`sort` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'порядок следования',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK_dones_live_together_dones` (`id_dones`) USING BTREE,
	CONSTRAINT `FK_dones_live_together_dones` FOREIGN KEY (`id_dones`) REFERENCES `speciald`.`dones` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE
)
COMMENT='Совместно проживающие для физического лица'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
