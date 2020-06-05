ALTER TABLE `dones`
	ADD COLUMN `id_face_belong` INT NOT NULL DEFAULT '0' COMMENT 'Принадлежность (физ/юр лицо) = face_belong.id' AFTER `official_destination`;