<?php 
	/* Скрипт для установки движка */ 
	
	/* TODO: доработать вопросы безопасности, проверку на "уже уставнолено" */
	/* TODO: проверка на ошибки установки, вывод rewrite_error html */
	
	mb_internal_encoding('UTF-8');
	define('SL', DIRECTORY_SEPARATOR);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style>
			body {
				text-align: center;
				margin-top: 200px;
			}
			div.test { 
				padding: 5px; 
				margin: 0 0 5px 0; 
				border: 1px solid #C7C500; 
				-webkit-border-radius: 5px; 
				-moz-border-radius: 5px; 
				border-radius: 5px; 
				overflow: hidden; 
				font-weight: bold;
				margin-bottom: 20px;
			}
			div.field { 
				padding: 5px; 
				margin: 0 300px 5px 100px;
				overflow: hidden; 
				margin-bottom: 10px;
				text-align: left;
				font-weight: normal;
			}
			div.field input, div.field select { 
				float: right;
			}
			span.success {
				padding: 0 50px;
				background: #CCFF99;
			}
			span.error {
				padding: 0 50px;
				background: #FF6666;
			}
		</style>
	<head>
	<body>
<?php	
	if (empty($_POST)) {
		$writable = is_writable(__DIR__);
		$config = is_readable(__DIR__.SL.'sample.config');
		$htaccess = is_readable(__DIR__.SL.'sample.htaccess');
		$mod_rewrite = in_array('mod_rewrite', apache_get_modules());
		
		$process = $writable && $config && $htaccess && $mod_rewrite;
?>
	<div class="test">
		<?php if ($writable) { ?>
			<span class="success">
				У инсталлятора есть права на запись
			</span>
		<?php } else { ?>
			<span class="error">
				У инсталлятора нет прав на запись
			</span>
			<br />Попробуйте команду "chmod 755" или "chmod 777", на корневую директорию сайта.
			<br />Не беспокойтесь, установщик после завершения уберет права на запись для большинства папок.
		<?php } ?>
	</div>
	<div class="test">
		<?php if ($config && $htaccess) { ?>
			<span class="success">
				Файлы конфигурации присутствуют и доступны
			</span>
		<?php } else { ?>
			<?php if (!$config) { ?>
				<span class="error">
					Недоступна папка sample.config
				</span>
				<br />Если папка присутствует в корневой директории, проверьте права на ее чтение			
			<?php } ?>
			<?php if (!$htaccess) { ?>
				<span class="error">
					Недоступен файл sample.htaccess
				</span>
				<br />Если файл присутствует в корневой директории, проверьте права на его чтение
			<?php } ?>
		<?php } ?>
	</div>
	<div class="test">
		<?php if ($mod_rewrite) { ?>
			<span class="success">
				Модуль mod_rewrite на сервере установлен и подключен
			</span>
		<?php } else { ?>
			<span class="error">
				Модуль mod_rewrite на сервере недоступен
			</span>
			<br />Если вы уверены, что он установлен, проверьте подключен ли он в конфигурации apache
		<?php } ?>
	</div>	
	<?php if ($process) { ?>
		<form method="post">
			<input type="submit" value="Перейти к заданию настроек" name="proceed">
		</form>
	<?php } else { ?>
		<br />
		<h3>Исправьте пункты помеченные красным и обновите страницу.</h3>
	<?php } ?>
<?php
	} elseif (!isset($_POST['subfolder'])) {		
?>		
	<form method="post">
		<div class="test">
			Аккаунт администратора
		</div>
		<div class="test">
			<div class="field">
				Логин: <input type="text" value="" name="admin[name]">
			</div>
			<div class="field">
				Емейл: <input type="text" value="" name="admin[mail]"> 
				(также используется при выводе некоторых ошибок)
			</div>
			<div class="field">
				Пароль: <input type="text" value="" name="admin[password]">
			</div>
		</div>
		<div class="test">
			Настройки базы данных
		</div>
		<div class="test">
			<div class="field">
				Тип базы данных: 
				<select type="text" name="database[type]">
					<option value="mysql">mysql</option>
					<option value="firebird">firebird</option>
				</select>
			</div>
			<div class="field">
				Адрес: <input type="text" value="localhost" name="database[server]"> 				
			</div>
			<div class="field">
				Пользователь: <input type="text" value="root" name="database[user]"> 				
			</div>
			<div class="field">
				Пароль: <input type="text" value="" name="database[password]"> 				
			</div>
			<div class="field">
				Название базы: <input type="text" value="" name="database[database]"> 				
			</div>
		</div>
		<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="subfolder">
		<input type="submit" value="Установить скрипт" name="proceed">
	</form>
<?php
	} else {
		$config_name = __DIR__.SL.'sample.config'.SL.'main.ini';
		$database_name = __DIR__.SL.'sample.config'.SL.'database.ini';
		$htaccess_name = __DIR__.SL.'sample.htaccess';
		
		$config = file_get_contents($config_name);
		$database = file_get_contents($database_name);
		$htaccess = file_get_contents($htaccess_name);
		
		$config = preg_replace('/(\[website\][^\[]*Directory\s*=)[^\n\r]*/s', '$1 '.$_POST['subfolder'], $config);
		
		$using = $_POST['database']['type'];
		unset($_POST['database']['type']);
		
		$database = preg_replace('/(Using\s*=)[^\n\r]*/si', '$1 '.$using, $database);
		
		foreach ($_POST['database'] as $name => $setting) {
			$database = preg_replace('/(\['.$using.'\][^\[]*'.$name.'\s*=)[^\n\r]*/si', '$1 '.$setting, $database);
		}
		
		$htaccess = preg_replace('/([\n\r]RewriteBase\s*)\/[^\n\r]*/', '$1 '.$_POST['subfolder'], $htaccess);
		
		$dir = opendir(__DIR__.SL.'sample.config'); 
		@mkdir(__DIR__.SL.'config'); 
		while(false !== ($file = readdir($dir))) { 
			if (($file != '.') && ($file != '..' ) && !is_dir(__DIR__.SL.'sample.config/'.$file)) { 
				copy(__DIR__.SL.'sample.config/'.$file, __DIR__.SL.'config/'.$file); 
			} 
		} 
		closedir($dir); 	
		
		copy($htaccess_name, __DIR__.SL.'.htaccess');
		
		file_put_contents(__DIR__.SL.'config'.SL.'main.ini', $config);
		file_put_contents(__DIR__.SL.'config'.SL.'database.ini', $database);
		file_put_contents(__DIR__.SL.'.htaccess', $htaccess);
		
?>
	<h3>Скрипт сайта успешно установлен. Обновите страницу.</h3>
<?php
	}
?>
