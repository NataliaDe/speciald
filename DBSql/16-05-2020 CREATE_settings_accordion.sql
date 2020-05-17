CREATE TABLE `settings_accordion` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_user` INT(11) NOT NULL DEFAULT '0' COMMENT 'пользователь, чья настройка',
	`id_dones` INT(11) NOT NULL DEFAULT '0' COMMENT 'донесение, чья настройка',
	`value` TEXT(65535) NULL DEFAULT NULL COMMENT 'значение настройки(закрытые вкладки)' COLLATE 'utf8_general_ci',
	`date_insert` DATETIME NULL DEFAULT NULL COMMENT 'когда создана запись',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK__users` (`id_user`) USING BTREE,
	INDEX `FK__dones` (`id_dones`) USING BTREE,
	CONSTRAINT `FK__dones` FOREIGN KEY (`id_dones`) REFERENCES `speciald`.`dones` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE,
	CONSTRAINT `FK__users` FOREIGN KEY (`id_user`) REFERENCES `speciald`.`users` (`id`) ON UPDATE RESTRICT ON DELETE CASCADE
)
COMMENT='настройки пользователя: если пользователь закрыл вкладку на форме создания стандартного СД - сохраняем сюда. при повторном открытии формы стандартного СД эта вкладка будет закрыта по умолчанию.'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;
