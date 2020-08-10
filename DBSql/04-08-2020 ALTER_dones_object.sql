ALTER TABLE `dones_object`
	ADD COLUMN `avto_mark` TEXT NOT NULL COMMENT 'Вид объекта "автотранспорт": поле "марка"' AFTER `avto_register_sign`;