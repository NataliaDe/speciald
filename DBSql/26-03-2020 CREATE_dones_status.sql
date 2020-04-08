CREATE TABLE `dones_status` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`id_dones` INT NOT NULL COMMENT 'ID СД=dones.id',
	`id_status` INT NOT NULL COMMENT '=actions.id',
	`description_refuse` TEXT NOT NULL COMMENT 'описание, почему СД отклонил',
	`id_user` INT NOT NULL COMMENT 'кто выставил статус=users.id',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
;

ALTER TABLE `dones_status`
	ADD CONSTRAINT `FK_dones_status_dones` FOREIGN KEY (`id_dones`) REFERENCES `dones` (`id`) ON DELETE CASCADE;

ALTER TABLE `dones_status`
	ADD CONSTRAINT `FK_dones_status_actions` FOREIGN KEY (`id_status`) REFERENCES `actions` (`id`) ON DELETE RESTRICT;

ALTER TABLE `dones_status`
	ADD CONSTRAINT `FK_dones_status_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);