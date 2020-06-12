ALTER TABLE `dones`
	ADD COLUMN `is_show_owner` INT NOT NULL DEFAULT '0' COMMENT 'Данные по собственнику: 1 - выводить в СД, 0 - нет' AFTER `law_face_name_owner`;


ALTER TABLE `dones`
	ADD COLUMN `owner_word` TEXT NOT NULL COMMENT 'Данные по собственнику: склеинный текст для вывода в WORD' AFTER `is_show_owner`;
ALTER TABLE `dones`
	ADD COLUMN `object_word` TEXT NOT NULL COMMENT 'Объект: склеинный текст для вывода в WORD' AFTER `owner_word`;