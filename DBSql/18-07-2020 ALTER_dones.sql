ALTER TABLE `dones`
	ADD COLUMN `situation_first_arrival` TEXT NOT NULL COMMENT 'Обстановка на момент прибытия первого подразделения' AFTER `people_status`;