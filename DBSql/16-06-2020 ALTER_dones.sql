ALTER TABLE `dones`
	ADD COLUMN `garnison_main` TEXT NOT NULL COMMENT 'Ответственный по гарнизону' AFTER `inspector`;

ALTER TABLE `dones`
	ADD COLUMN `is_opg` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '1 - выезжала ОПГ, 0 - нет' AFTER `is_not_involved_str`,
	ADD COLUMN `opg_text` TEXT NOT NULL COMMENT 'Примечание для ОПГ' AFTER `is_opg`;