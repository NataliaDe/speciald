ALTER TABLE `dones_object`
	ADD COLUMN `api_date` DATE NULL DEFAULT NULL COMMENT 'Объект: дата установки АПИ (физ.лицо)' AFTER `object_roof`,
	ADD COLUMN `id_api_source` INT NOT NULL DEFAULT '0' COMMENT 'Объект: источник финансирования для установки АПИ (физ.лицо) = api_source.id' AFTER `api_date`,
	ADD COLUMN `is_api_worked` TINYINT NOT NULL DEFAULT '0' COMMENT 'Объект: 1 - сработал АПИ, 0 - нет. (физ.лицо)' AFTER `id_api_source`,
	ADD COLUMN `is_api_influence` TINYINT NOT NULL DEFAULT '0' COMMENT 'Объект: 1 - сработка АПИ повлияла на обнаружение загорания,  0 - нет. (физ.лицо)' AFTER `is_api_worked`,
	ADD COLUMN `is_aps` TINYINT NOT NULL DEFAULT '0' COMMENT 'Объект: 1 - наличие АПС, 0 - нет. (юр.лицо)' AFTER `is_api_influence`,
	ADD COLUMN `aps_name` TEXT NOT NULL COMMENT 'Объект: название АПС (юр.лицо)' AFTER `is_aps`,
	ADD COLUMN `is_aps_worked` TINYINT NOT NULL DEFAULT '0' COMMENT 'Объект: 1 - АПС сработала, 0 - нет. (юр.лицо)' AFTER `aps_name`,
	ADD COLUMN `is_aps_influence` TINYINT NOT NULL DEFAULT '0' COMMENT 'Объект: 1 - сработка АПС повлияла на обнаружение загорания, 0 - нет. (юр.лицо)' AFTER `is_aps_worked`;
