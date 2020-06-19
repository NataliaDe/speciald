CREATE TABLE `filter_range_sd` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_range` INT(11) NOT NULL DEFAULT '0' COMMENT 'СД за : 0 - все, 1-за месяц, 2-за год',
	`id_user` INT(11) NOT NULL DEFAULT '0',
	`date_create` DATETIME NOT NULL COMMENT 'когда создана запись',
	`last_update` DATETIME NOT NULL COMMENT 'последний раз редактировалась',
	PRIMARY KEY (`id`),
	INDEX `FK_filter_range_sd_users` (`id_user`),
	CONSTRAINT `FK_filter_range_sd_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
)
COMMENT='Хранит выбранный период отображения СД (все, за месяц, за год)'
ENGINE=InnoDB
;
