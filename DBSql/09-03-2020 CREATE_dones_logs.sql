CREATE TABLE `dones_logs` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_user` INT(11) NOT NULL DEFAULT '0' COMMENT 'кто выполнял',
	`id_dones` INT(11) NOT NULL DEFAULT '0' COMMENT 'с каким донесением',
	`id_action` INT(11) NOT NULL DEFAULT '0' COMMENT 'что делал = actions.id',
	`date_action` DATETIME NULL DEFAULT NULL COMMENT 'когда делал',
	PRIMARY KEY (`id`)
)
COMMENT='Логи'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;
