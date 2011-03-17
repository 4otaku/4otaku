#!/usr/bin/php
<?
	set_time_limit(3600);
	ini_set('memory_limit', '512M');
	
	include 'init.php';
	
	$cron = new Cron();
	
	if (is_array($argv) && !empty($argv[1])) {		
		$tasks = array_slice($argv, 1);
	} else {
		$tasks = $cron->get_task_list();	
	}	

	if (is_array($tasks)) {
		foreach ($tasks as $task) {
			echo $cron->do_task($task);
		}
	}
