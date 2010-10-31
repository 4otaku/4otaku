<?

function __autoload($class_name) {
	$class = 'libs/' . str_replace('__','/',$class_name) . '.php';
    if (file_exists($class)) include_once $class;
    else {
		include_once 'templates/404/fatal.php';
		ob_end_flush();
		die;
    }
}

function myoutput($buffer) {
	return str_replace(array("\t","  ","\n","\r"),array(""," ","",""),$buffer);
}

mb_internal_encoding('UTF-8');
if (strpos($_SERVER["REQUEST_URI"], 'art/download') === false && !strpos($_SERVER["REQUEST_URI"], '/rss/')) ob_start('myoutput');
include_once 'engine/config.php';

$db = new mysql();
$check = new check_values();

include_once 'engine/cleanglobals.php';
include_once 'engine/metafunctions.php';

if ($check->hash($_COOKIE['settings'])) $settings = $db->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
if ($settings) {
	setcookie("settings", $_COOKIE['settings'], time()+3600*24*60, '/' , '.4otaku.ru');
	$sets = merge_settings($sets,$hide_sape = unserialize(base64_decode($settings)));
}
else {
	$hash = md5(microtime(true));
	setcookie("settings", $hash, time()+3600*24*60, '/' , '.4otaku.ru');
	$db->sql('insert into settings (cookie,lastchange) values ("'.$hash.'","'.time().'")',0);
	$_COOKIE['settings'] = $hash;
}

$url = array_filter(explode('/',preg_replace('/\?[^\/]+$/','',$_SERVER["REQUEST_URI"]))); unset($url[0]); if (!$url[1]) $url[1] = 'index';
include_once 'engine/handle_old_urls.php';

/*
	if (!defined('_SAPE_USER')){
		define('_SAPE_USER', '75cbf01b1205673e07687fe898e7afec'); 
	}
	include_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php'); 
	$o['charset'] = 'UTF-8'; $sape = new SAPE_client($o); unset($o);
	$sape_links = $sape->return_links();
*/

if ($post['do']) {
	$post['do'] = explode('.',$post['do']);
	if (count($post['do']) == 2) {
		$input_class = 'input__'.$post['do'][0]; $input = new $input_class;
		$input_function = $post['do'][1]; $input->$input_function($post);		
	}
}

$data = array();

$output_class = 'output__'.$url[1]; $output = new $output_class;

$output->check_404($output->allowed_url); 
if (!$error) 
	$data['main'] = $output->get_data();
	
$data = array_merge($data,$output->get_side_data($output->side_modules));
if ($error) 
	$output->make_404($output->error_template);

include_once 'templates/'.str_replace('__','/',$output->template).'.php';
ob_end_flush();
