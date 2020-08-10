CREATE TABLE `filter_user` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_user` INT(11) NOT NULL DEFAULT '0' COMMENT 'пользователь',
	`value` TEXT NOT NULL COMMENT 'значение фильтров в каталоге',
	`date_create` DATETIME NOT NULL COMMENT 'когда создана запись',
	`last_update` DATETIME NOT NULL COMMENT 'последний раз редактировалась',
	PRIMARY KEY (`id`),
	INDEX `FK_filter_user` (`id_user`),
	CONSTRAINT `FK_filter_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
COMMENT='Хранит выбранные пользователем  фильтры в каталоге'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
