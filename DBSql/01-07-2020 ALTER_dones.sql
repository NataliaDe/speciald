ALTER TABLE `dones`
	ADD COLUMN `file_pdf` TEXT NULL COMMENT 'прикрепленный файл СД в формате pdf' AFTER `file_doc`;