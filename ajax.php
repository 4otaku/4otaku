<?

function __autoload($class_name) {
    include_once 'libs/' . str_replace('__','/',$class_name) . '.php';
}

function myoutput($buffer) {
	if (strpos($buffer,'<textarea')) return $buffer;
    return str_replace(array("\t","  ","\n","\r"),array(""," ","",""),$buffer);
}

mb_internal_encoding('UTF-8');
ob_start('myoutput');
include_once 'engine/config.php';

$db = new mysql();
$check = new check_values();

include_once 'engine/cleanglobals.php';
include_once 'engine/metafunctions.php';

if ($check->hash($_COOKIE['settings'])) $settings = $db->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
if ($settings) {
	setcookie("settings", $_COOKIE['settings'], time()+3600*24*60, '/' , '.4otaku.ru');
	$sets = merge_settings($sets,unserialize(base64_decode($settings)));
}

$output_class = 'dinamic__'.$get['m'];
$output = new $output_class();

$func = $get['f'];

$data = $output->$func();
if ($output->template || $data) {
	if ($output->textarea) ob_end_clean();
    if ($output->template) include_once($output->template);
    else include_once 'templates/dinamic/'.$get['m'].'/'.$get['f'].'.php';
}

if ($postparse) {
	preg_match($postparse, ob_get_clean(), $buffer);
	echo myoutput(end($buffer));
} else {
	ob_end_flush();
}
