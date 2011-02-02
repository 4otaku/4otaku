#!/usr/bin/php
<?

include_once 'inc.common.php';

$cron = new cron();
/*
if (is_array($argv) && in_array('slow', $argv)) {
	$time = time();
	$tasks = obj::db()->sql('select * from cron where time < '.$time.' and period >= 2700');
	set_time_limit(1800);
	ini_set('memory_limit', '1024M');
}
*/
if ($key = key($_GET)) {
	$cron->$key();
	var_dump(memory_get_peak_usage(true));
	exit();
} else {
	$time = time();
	$tasks = obj::db()->sql('select * from cron where time < '.$time);
}

if (is_array($tasks)) foreach ($tasks as $task) if (method_exists($cron,$task['function'])) {
	$func = $task['function'];
	$cron->$func(); $nexttime = $time + $task['period'] - 15;
	obj::db()->update('cron','time',$nexttime,$task['id']);
}
