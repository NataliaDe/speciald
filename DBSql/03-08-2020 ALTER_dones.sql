ALTER TABLE `dones`
	ADD COLUMN `type_template` TEXT NOT NULL COMMENT 'тип шаблона: common, user' AFTER `is_show_prevention`,
	ADD COLUMN `id_template` TEXT NOT NULL COMMENT 'краткое название шаблона. с этого слова начинаются все поля в таблице, которые имеют отношение к этому шаблону' AFTER `type_template`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_id_short_description` INT NOT NULL DEFAULT '0' COMMENT '=journal.workview.id' AFTER `id_template`,
	ADD COLUMN `ct_1_id_goal_rig` INT NOT NULL DEFAULT '0' COMMENT '=journal.workview.id' AFTER `ct_1_id_short_description`,
	ADD COLUMN `ct_1_goal_rig` TEXT NOT NULL COMMENT 'Цель выезда (вид работ)' AFTER `ct_1_id_goal_rig`;


ALTER TABLE `dones`
	ADD COLUMN `ct_1_object` TEXT NOT NULL COMMENT 'Характеристика объекта' AFTER `ct_1_goal_rig`,
	ADD COLUMN `ct_1_applicant` TEXT NOT NULL COMMENT 'От кого поступило' AFTER `ct_1_object`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_silymchs` TEXT NOT NULL COMMENT 'Направленные СиС' AFTER `ct_1_applicant`,
	ADD COLUMN `ct_1_senior` TEXT NOT NULL COMMENT 'старший' AFTER `ct_1_silymchs`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_innerservice` TEXT NOT NULL COMMENT 'службы взаимодействия' AFTER `ct_1_senior`,
	ADD COLUMN `ct_1_arrival_situation` TEXT NOT NULL COMMENT 'Обстановка по прибытию' AFTER `ct_1_innerservice`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_come_in` TEXT NOT NULL COMMENT 'как проникали' AFTER `ct_1_arrival_situation`,
	ADD COLUMN `ct_1_taken_measures` TEXT NOT NULL COMMENT 'принятые меры' AFTER `ct_1_come_in`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_affected` TEXT NOT NULL COMMENT 'пострадавшие' AFTER `ct_1_taken_measures`,
	ADD COLUMN `ct_1_effects` TEXT NOT NULL COMMENT 'последствия' AFTER `ct_1_affected`,
	ADD COLUMN `ct_1_note` TEXT NOT NULL COMMENT 'примечание (категория собственника или проживающего....)' AFTER `ct_1_effects`;

ALTER TABLE `dones`
	ADD COLUMN `ct_1_position_sign` TEXT NOT NULL COMMENT 'должность подписывающего СД' AFTER `ct_1_note`,
	ADD COLUMN `ct_1_podr_sign` TEXT NOT NULL COMMENT 'подразделение подписывающего СД' AFTER `ct_1_position_sign`,
	ADD COLUMN `ct_1_rank_sign` TEXT NOT NULL COMMENT 'звание подписывающего СД' AFTER `ct_1_podr_sign`,
	ADD COLUMN `ct_1_fio_sign` TEXT NOT NULL COMMENT 'ФИО подписывающего СД' AFTER `ct_1_rank_sign`;