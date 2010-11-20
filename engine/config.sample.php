<?

/* Конфиг, ня. Заполняем подключение к БД и удаляем .sample из имени файла */

// Соединение с базой данных

$def['db']['host'] = 'localhost';
$def['db']['user'] = '';
$def['db']['pass'] = '';
$def['db']['main_db'] = '';
$def['db']['sub_db'] = '';
$def['db']['chat_db'] = '';

// Загрузка файлов

$def['booru']['filesize'] = 10*1024*1024;
$def['booru']['thumbsize'] = 150;
$def['booru']['largethumbsize'] = 250;
$def['booru']['resizewidth'] = 750;
$def['booru']['resizestep'] = 1.1;
$def['post']['picturesize'] = 2*1024*1024;
$def['post']['filesize'] = 10*1024*1024;
$def['video']['filesize'] = 50*1024*1024;

// Размеры видеороликов

$sets['video']['thumb'] = '480x360';
$sets['video']['full'] = '720x540';

// Непотребства и переводы, 1  - показывать, 0 - не показывать

$sets['show']['nsfw'] = 0;
$sets['show']['yaoi'] = 0;
$sets['show']['guro'] = 0;
$sets['show']['furry'] = 0;
$sets['show']['translation'] = 1;

// Большие тамбнейлы, ресайз изображений, 1 - да, 0 - нет

$sets['art']['largethumbs'] = 0;
$sets['art']['resized'] = 1;

// Слайдшоу

$sets['slideshow']['resize'] = 1;
$sets['slideshow']['auto'] = 0;
$sets['slideshow']['delay'] = 5;

// Количество постов/комментов/прочего на страницу

$sets['pp']['post'] = 5;
$sets['pp']['search'] = 5;
$sets['pp']['comment_in_post'] = 7;
$sets['pp']['comment_in_line'] = 5;
$sets['pp']['updates_in_line'] = 5;
$sets['pp']['video'] = 5;
$sets['pp']['art'] = 30;
$sets['pp']['art_tags'] = 20;
$sets['pp']['art_pool'] = 40;
$sets['pp']['art_cg_pool'] = 20;
$sets['pp']['tags'] = 40;
$sets['pp']['news'] = 5;
$sets['pp']['latest_comments'] = 3;
$sets['pp']['random_orders'] = 5;

// Свернутость/равзернутость блоков меню, направление комментов 
// 1 - развернутый блок/инвертированное дерево, 0 - свернутый блок/обычное дерево

$sets['dir']['navi'] = 1;
$sets['dir']['comment'] = 1;
$sets['dir']['update'] = 1;
$sets['dir']['order'] = 1;
$sets['dir']['quick'] = 1;
$sets['dir']['tag'] = 1;
$sets['dir']['art_tag'] = 1;
$sets['dir']['comments_tree'] = 1;

// Имя/мыло пользователя по умолчанию

$def['user']['author'] = 'Анонимус';
$def['user']['name'] = 'Анонимно';
$def['user']['mail'] = 'default@avatar.mail';

$sets['user']['name'] = 'Анонимно';
$sets['user']['mail'] = 'default@avatar.mail';

//

$sets['rss']['default'] = 'pvun';
$def['rss']['default'] = 'pvun';

// Области сайта

$def['area'][] = 'main';
$def['area'][] = 'workshop';
$def['area'][] = 'flea_market';

// Разделы сайта

$def['type'][] = 'post';
$def['type'][] = 'video';
$def['type'][] = 'art';
