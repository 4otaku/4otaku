<?php

if (PHP_SAPI != 'cli') die;

include '../inc.common.php';

$next = false;
$cron = new Cron();
while ($next < 1426802400) {
	$points = file('last.logs');
	$last = max($points);
	$next = $last + 1800;

	$cron->get_logs(false, false, ($last - 60) * 1000 . '.' . ($next + 60) * 1000);

	file_put_contents('last.logs', "\n" . $next, FILE_APPEND);
}
