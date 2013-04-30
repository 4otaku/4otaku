<?php

if (PHP_SAPI != 'cli') die;

include '../inc.common.php';

$cron = new Cron();
$cron->get_logs(true);
