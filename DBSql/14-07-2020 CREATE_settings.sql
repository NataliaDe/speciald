INSERT INTO `settings` (`id`, `name`, `type`, `is_multi`, `role`, `type_sd`) VALUES
	(6, 'Режим загрузки медиаматериалов', 'mode_load_media', 0, 'creator', 0);

INSERT INTO `settings_options` (`id`, `id_settings`, `name`, `option`, `is_default`) VALUES
	(11, 6, 'Стандартный', 'standart', 1),
	(12, 6, 'Расширенный', 'wide', 0);