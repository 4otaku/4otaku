<?php

$link = $_GET['link'];
include '../inc.common.php';

if (is_numeric($link)) {
	$link = Database::get_field('post_url', 'url', $link);
}

$worker = new Cron_Post_Gouf();
$worker->test($link);
