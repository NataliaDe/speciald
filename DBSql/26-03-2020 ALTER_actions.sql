UPDATE `speciald`.`actions` SET `name`='подтвердено УМЧС' WHERE  `id`=4;
SELECT  `id`,  LEFT(`name`, 256) FROM `speciald`.`actions` LIMIT 1000;
SHOW CREATE TABLE `speciald`.`actions`;
UPDATE `speciald`.`actions` SET `name`='отклонено УМЧС' WHERE  `id`=5;
SELECT `id`, `name` FROM `speciald`.`actions` WHERE  `id`=5;
INSERT INTO `speciald`.`actions` (`name`) VALUES ('подтверждено РЦУРЧС');
SELECT LAST_INSERT_ID();
SELECT `id`, `name` FROM `speciald`.`actions` WHERE  `id`=6;
INSERT INTO `speciald`.`actions` (`name`) VALUES ('отклонено РЦУРЧС');
SELECT LAST_INSERT_ID();
SELECT `id`, `name` FROM `speciald`.`actions` WHERE  `id`=7;
INSERT INTO `speciald`.`actions` (`name`) VALUES ('открытие доступа на редактирование');
SELECT LAST_INSERT_ID();
SELECT `id`, `name` FROM `speciald`.`actions` WHERE  `id`=8;
INSERT INTO `speciald`.`actions` (`name`) VALUES ('закрытие доступа на редактирование');
SELECT LAST_INSERT_ID();
SELECT `id`, `name` FROM `speciald`.`actions` WHERE  `id`=9;



ALTER TABLE `dones_logs`
	ADD CONSTRAINT `FK_dones_logs_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);


ALTER TABLE `dones_logs`
	ADD CONSTRAINT `FK_dones_logs_dones` FOREIGN KEY (`id_dones`) REFERENCES `dones` (`id`) ON DELETE CASCADE;

ALTER TABLE `dones_logs`
	ADD CONSTRAINT `FK_dones_logs_actions` FOREIGN KEY (`id_action`) REFERENCES `actions` (`id`);