<?
	if (empty($_POST)) die("error");
	
	include '../engine/init.php';
	
	$db = new Database_Mysql($_POST);
	
	$posts = $db->get_table('post');
	
	
	var_dump($posts);
	
	
	
	echo "Hooray!";
