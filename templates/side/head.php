<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru-RU"<?=($url[1] == 'index' ? ' class="wrapwindow"' : '');?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<title><?=$data['head']['title'];?></title>
	<link rel="stylesheet" href="<?=$def['site']['dir']?>/jss/m/?b=jss&f=plugins.css,main.css,header.css<?=(sets::user('rights') ? ',admin.css' : '');?>&ver=23" type="text/css" media="screen" />
	<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/config.js"></script>
	<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=lock.js,jquery-1.6.2.min.js,plugins.js,main.js
		<?=(sets::user('rights') ? ',admin.js' : '');?>
		<?=(sets::plugins(1) ? ',plugin/censor.js' : '');?>
		<?=(sets::plugins(2) ? ',plugin/hider.js' : '');?>
		&ver=36"></script>
</head>
