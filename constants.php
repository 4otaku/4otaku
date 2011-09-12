<?php

	if (file_exists(ROOT_DIR.SL.'custom_templates')) {
		define('TEMPLATE_DIR', ROOT_DIR.SL.'custom_templates');
	} else {
		define('TEMPLATE_DIR', ROOT_DIR.SL.'templates');
	}

	define('CACHE', ROOT_DIR.SL.'cache');
	define('FILES', ROOT_DIR.SL.'files');
	define('IMAGES', ROOT_DIR.SL.'images');

	define('MINUTE', 60);
	define('HOUR', MINUTE * 60);
	define('DAY', HOUR * 24);
	define('WEEK', DAY * 7);
	define('MONTH', DAY * 30);

	define('KILOBYTE', 1024);
	define('MEGABYTE', KILOBYTE * 1024);
	define('GIGABYTE', MEGABYTE * 1024);
