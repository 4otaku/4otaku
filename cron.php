#!/usr/bin/php
<?php

include_once 'inc.common.php';

$cron = new cron();

ini_set('memory_limit', '1024M');
ini_set('time_limit', '1800');

if ($key = key($_GET)) {
	$cron->$key();
	var_dump(memory_get_peak_usage(true));
	exit();
}

$time = Database::unix_to_date();
$tasks = Database::get_vector('cron', array('function', 'period'), 'last_time < ?', $time);

if (empty($tasks)) {
	exit();
}

foreach ($tasks as $task => $period) {
	if (method_exists($cron, $task)) {

		$cron->$task();

		$nexttime = Database::unix_to_date(Transform_Time::parse($period) - 15);
		Database::update('cron', 'function = ?', array('last_time' => $nexttime), $task);
	}
}
