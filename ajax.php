<?

include_once 'inc.common.php';

$check = new check_values();

include_once 'engine'.SL.'cleanglobals.php';
include_once 'engine'.SL.'metafunctions.php';

if ($check->hash($_COOKIE['settings'])) $settings = $db->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
if ($settings) {
	$cookie_domain = $_SERVER['SERVER_NAME'] == 'localhost' ? false : '.'.$_SERVER['SERVER_NAME'];
	setcookie("settings", $_COOKIE['settings'], time()+3600*24*60, '/' ,  $cookie_domain);
	$sets = merge_settings($sets,unserialize(base64_decode($settings)));
}

$output_class = 'dinamic__'.$get['m'];
$output = new $output_class();

$func = $get['f'];

$data = $output->$func();
if (@$output->template || $data)
{
    if (@$output->textarea) ob_end_clean();
    if (@$output->template) include_once($output->template);
    else include_once 'templates'.SL.'dinamic'.SL.$get['m'].SL.$get['f'].'.php';
}

if ($postparse) {
	preg_match($postparse, ob_get_clean(), $buffer);
	echo myoutput(end($buffer));
} else {
	ob_end_flush();
}
