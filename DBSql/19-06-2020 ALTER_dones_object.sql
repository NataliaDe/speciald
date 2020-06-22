ALTER TABLE `dones_object`
	ADD COLUMN `object_floor_flat` INT NOT NULL DEFAULT '0' COMMENT 'Этаж  квартиры, если выбран многоквартирный жилой дом' AFTER `object_floor`,
	ADD COLUMN `object_cnt_rooms` INT NOT NULL DEFAULT '0' COMMENT 'кол-во комнат  квартиры, если выбран многоквартирный жилой дом' AFTER `object_floor_flat`;