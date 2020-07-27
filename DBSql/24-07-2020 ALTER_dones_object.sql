ALTER TABLE `dones_object`
	ADD COLUMN `type_damage` TEXT NOT NULL COMMENT 'Объект: тип повреждения' AFTER `object`;


ALTER TABLE `dones_object`
	ADD COLUMN `avto_vid` INT NOT NULL DEFAULT '0' COMMENT 'Вид объекта "автотранспорт": поле "вид"=avtotransport_vid.id' AFTER `is_aps_influence`,
	ADD COLUMN `avto_year` INT NOT NULL DEFAULT '0' COMMENT 'Вид объекта "автотранспорт": поле "год выпуска"' AFTER `avto_vid`,
	ADD COLUMN `avto_type_fuel` TEXT NOT NULL COMMENT 'Вид объекта "автотранспорт": поле "тип топлива"' AFTER `avto_year`,
	ADD COLUMN `avto_register_sign` TEXT NOT NULL COMMENT 'Вид объекта "автотранспорт": поле "регистрационный знак"' AFTER `avto_type_fuel`;