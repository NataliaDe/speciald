CREATE TABLE `face_belong` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	PRIMARY KEY (`id`)
)
COMMENT='раздел "принадлежность". '
ENGINE=InnoDB
;

INSERT INTO `face_belong` (`name`) VALUES ('физическое лицо');
INSERT INTO `face_belong` (`name`) VALUES ('юридическое лицо');