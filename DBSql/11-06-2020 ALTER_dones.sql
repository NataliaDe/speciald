ALTER TABLE `dones`
	ADD COLUMN `is_show_opening_descr` TINYINT NOT NULL DEFAULT '0' COMMENT '1 - выводить в СД содержание поступившей информации, 0 - выводить  в СД склеинный текст. в люб.случае берем из поля opening_word ' AFTER `opening_description`,
	ADD COLUMN `opening_word` TEXT NOT NULL COMMENT 'описание, которе выводим в Word в первый абзац' AFTER `is_show_opening_descr`;