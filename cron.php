#!/usr/bin/php
<?php

include_once 'inc.common.php';

define('LOCK_FILE', '/tmp/cron_lock');

$cron = new Cron();

ini_set('memory_limit', '1024M');
ini_set('time_limit', '1800');

if ($key = key($_GET)) {
	$cron->process($key);
	var_dump(memory_get_peak_usage(true));

	exit();
}

if (file_exists(LOCK_FILE) && (filemtime(LOCK_FILE) > time() - 3600)) {
	exit();
}

$time = Database::unix_to_date();
$tasks = Database::order('id', 'asc')
	->get_vector('cron', array('function', 'period'), 'last_time < ?', $time);

if (empty($tasks)) {
	exit();
}

touch(LOCK_FILE);

foreach ($tasks as $task => $period) {

	$cron->process($task);

	$nexttime = Database::unix_to_date(Transform_Time::parse($period) - 15);
	Database::update('cron', array('last_time' => $nexttime), 'function = ?', $task);
}

unlink(LOCK_FILE);
