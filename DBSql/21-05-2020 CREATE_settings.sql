CREATE TABLE `settings` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TEXT NULL COMMENT 'название настройки',
	`type` TEXT NULL COMMENT 'код настройки',
	`is_multi` TINYINT(2) NULL DEFAULT '0' COMMENT '1-настройка имеет несколько вариантов в settings_options',
	`role` VARCHAR(50) NULL DEFAULT 'creator' COMMENT 'creator - настройка для создателя, viewer - для роли просмоторщика all - для всех',
	`type_sd` TINYINT(2) NULL DEFAULT '1' COMMENT 'тип СД, для которого активна настройка: 1 - стандартное 2 -простое, 0 - для всех СД',
	PRIMARY KEY (`id`)
)
COMMENT='настройки, доступные пользователю'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;


CREATE TABLE `settings_options` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_settings` INT(11) NOT NULL DEFAULT '0' COMMENT '=settings.id. Настройка',
	`name` TEXT NULL COMMENT 'название опции для настройки',
	`option` TEXT NULL COMMENT 'название опции для настройки как код',
	`is_default` TINYINT(2) NOT NULL DEFAULT '1' COMMENT '1 - опция установлена по умолчанию, 0 - нет',
	PRIMARY KEY (`id`),
	INDEX `FK_settings_options_settings` (`id_settings`),
	CONSTRAINT `FK_settings_options_settings` FOREIGN KEY (`id_settings`) REFERENCES `settings` (`id`) ON DELETE CASCADE
)
COMMENT='опции для настроек таблицы settings'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5
;



CREATE TABLE `settings_options_users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_settings_option` INT(11) NOT NULL DEFAULT '0' COMMENT 'опция настройки = settings_options.id',
	`id_user` INT(11) NOT NULL DEFAULT '0' COMMENT 'пользователь, чья настройка = users.id',
	`last_update` DATETIME NULL DEFAULT NULL COMMENT 'дата создания/обновления записи',
	PRIMARY KEY (`id`),
	INDEX `FK_settings_options_users_settings_options` (`id_settings_option`),
	INDEX `FK_settings_options_users_users` (`id_user`),
	CONSTRAINT `FK_settings_options_users_settings_options` FOREIGN KEY (`id_settings_option`) REFERENCES `settings_options` (`id`) ON DELETE CASCADE,
	CONSTRAINT `FK_settings_options_users_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
COMMENT='настройки конкретного пользователя'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=15
;
