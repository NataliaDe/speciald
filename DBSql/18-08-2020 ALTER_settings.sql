INSERT INTO `speciald`.`settings` (`name`, `type`, `type_sd`) VALUES ('Выводить заявителя в начальный текст', 'is_people_to_open_text', '1');


INSERT INTO `speciald`.`settings_options` (`id_settings`, `name`, `option`) VALUES ('7', 'Нет', 'no');

INSERT INTO `speciald`.`settings_options` (`id_settings`, `name`, `option`, `is_default`) VALUES ('7', 'Да', 'yes', '0');


INSERT INTO `speciald`.`settings` (`name`, `type`) VALUES ('Обстановка на момент прибытия первого подразделения', 'is_situation_first_arrival');
INSERT INTO `speciald`.`settings_options` (`id_settings`, `name`, `option`) VALUES ('8', 'Не отображать', 'no');
INSERT INTO `speciald`.`settings_options` (`id_settings`, `name`, `option`, `is_default`) VALUES ('8', 'Отображать', 'yes', '0');