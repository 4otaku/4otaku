TRUNCATE TABLE api.cron_log;
TRUNCATE TABLE api.slack_log;
TRUNCATE TABLE api.slack_user;
TRUNCATE TABLE api.ip_ban;
UPDATE api.user SET pass = '202cb962ac59075b964b07152d234b70'; -- pass=123
UPDATE api.log SET ip = 2130706433; -- ip=127.0.0.1
UPDATE api.comment SET ip = 2130706433; -- ip=127.0.0.1

UPDATE 4otaku.comment SET ip = '127.0.0.1';
UPDATE 4otaku.board SET ip = '127.0.0.1';

-- old art menu
UPDATE api.`head_menu` SET `name` = 'Основной раздел',`url` = '/art/',`parent` = 5,`order` = 14 WHERE `head_menu`.`id` = 15;
UPDATE api.`head_menu` SET `name` = 'Премодерация',`url` = '/art/workshop/',`parent` = 5,`order` = 15 WHERE `head_menu`.`id` = 16;
UPDATE api.`head_menu` SET `name` = 'Барахолка',`url` = '/art/flea_market/',`parent` = 5,`order` = 16 WHERE `head_menu`.`id` = 17;
UPDATE api.`head_menu` SET `name` = 'Группы',`url` = '/art/pool/',`parent` = 5,`order` = 17 WHERE `head_menu`.`id` = 18;
UPDATE api.`head_menu` SET `name` = 'CG паки',`url` = '/art/cg_packs/',`parent` = 5,`order` = 18 WHERE `head_menu`.`id` = 19;
UPDATE api.`head_menu` SET `name` = 'Спрайты',`url` = '/art/sprites/',`parent` = 5,`order` = 19 WHERE `head_menu`.`id` = 20;
UPDATE api.`head_menu` SET `name` = 'Комментарии',`url` = '/comments/art/',`parent` = 5,`order` = 57 WHERE `head_menu`.`id` = 59;
DELETE FROM api.`head_menu` WHERE id IN (67, 68, 69, 70); -- new art menu

REPLACE INTO api.`head_menu` (`id`, `name`, `url`, `parent`, `order`, `separator`) VALUES
(71, 'Старое', '#', 0, 67, 0),
(72, 'Турнир по Touhou Hisoutensoku', '/soku/', 71, 7, 1),
(73, 'Логи main', '/logs/', 71, 1, 0),
(74, 'Логи hisouten', '/logs_hisouten', 71, 2, 0),
(75, 'Логи homestuck', '/logs_homestuck/', 71, 3, 0),
(76, 'Логи kikaki', '/logs_kikaki/', 71, 4, 0),
(77, 'Логи robust', '/logs_robust/', 71, 5, 0),
(78, 'Логи vn', '/logs_vn/', 71, 6, 0)
;