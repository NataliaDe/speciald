ALTER TABLE `dones_media`
	CHANGE COLUMN `type` `type` ENUM('video','photo','audio') NOT NULL COMMENT 'тип медии' AFTER `file`;