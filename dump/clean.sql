TRUNCATE TABLE api.cron_log;
TRUNCATE TABLE api.slack_log;
TRUNCATE TABLE api.slack_user;
TRUNCATE TABLE api.ip_ban;
UPDATE api.user SET pass = '202cb962ac59075b964b07152d234b70'; -- pass=123
UPDATE api.log SET ip = 2130706433; -- ip=127.0.0.1
UPDATE api.comment SET ip = 2130706433; -- ip=127.0.0.1

UPDATE 4otaku.comment SET ip = '127.0.0.1'; -- ip=127.0.0.1

-- old art menu
UPDATE api.`head_menu` SET `name` = 'Основной раздел',`url` = '/art/',`parent` = 5,`order` = 14 WHERE `head_menu`.`id` = 15;
UPDATE api.`head_menu` SET `name` = 'Премодерация',`url` = '/art/workshop/',`parent` = 5,`order` = 15 WHERE `head_menu`.`id` = 16;
UPDATE api.`head_menu` SET `name` = 'Барахолка',`url` = '/art/flea_market/',`parent` = 5,`order` = 16 WHERE `head_menu`.`id` = 17;
UPDATE api.`head_menu` SET `name` = 'Группы',`url` = '/art/pool/',`parent` = 5,`order` = 17 WHERE `head_menu`.`id` = 18;
UPDATE api.`head_menu` SET `name` = 'CG паки',`url` = '/art/cg_packs/',`parent` = 5,`order` = 18 WHERE `head_menu`.`id` = 19;
UPDATE api.`head_menu` SET `name` = 'Спрайты',`url` = '/art/sprites/',`parent` = 5,`order` = 19 WHERE `head_menu`.`id` = 20;
UPDATE api.`head_menu` SET `name` = 'Комментарии',`url` = '/comments/art/',`parent` = 5,`order` = 57 WHERE `head_menu`.`id` = 59;
DELETE FROM api.`head_menu` WHERE id IN (67, 68, 69, 70); -- new art menu