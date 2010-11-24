<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru-RU"<?=($url[1] == 'index' ? ' class="wrapwindow"' : '');?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<title><?=$data['head']['title'];?></title>
	<script type="text/javascript" src="/jss/m/?b=jss&f=jquery-1.3.2.min.js,box.js,main.js<?=($sets['user']['rights'] ? ',admin.js' : '');?>"></script>
	<link rel="stylesheet" href="/jss/m/?b=jss&f=box.css,main.css" type="text/css" media="screen" />
</head>
