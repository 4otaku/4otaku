<?

#include_once 'pre_config.php';

#include_once SITE_FDIR._SL.'inc.common.php';
include_once 'inc.common.php';

$check = new check_values();

include_once SITE_FDIR._SL.'engine'.SL.'cleanglobals.php';
include_once SITE_FDIR._SL.'engine'.SL.'metafunctions.php';

if ($check->hash($_COOKIE['settings'])) $settings = $db->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
if ($settings) {
	$cookie_domain = $_SERVER['SERVER_NAME'] == 'localhost' ? false : '.'.$_SERVER['SERVER_NAME'];	
	setcookie("settings", $_COOKIE['settings'], time()+3600*24*60, '/' , $cookie_domain);
	$sets = merge_settings($sets,$hide_sape = unserialize(base64_decode($settings)));
}
else {
	$hash = md5(microtime(true));
	$cookie_domain = $_SERVER['SERVER_NAME'] == 'localhost' ? false : '.'.$_SERVER['SERVER_NAME'];
	setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
	$db->sql('insert into settings (cookie,lastchange) values ("'.$hash.'","'.time().'")',0);
	$_COOKIE['settings'] = $hash;
}

#$url = array_filter(explode('/',preg_replace('/\?[^\/]+$/','',$_SERVER["REQUEST_URI"]))); 
if (strstr($_SERVER["REQUEST_URI"],SITE_DIR))
    {
    $tmpurl=".".substr($_SERVER["REQUEST_URI"],strlen(SITE_DIR));
    }
else
    {
    $tmpurl=$_SERVER["REQUEST_URI"];
    }

$url = array_filter(explode('/',preg_replace('/\?[^\/]+$/','',$tmpurl)));

#".".substr($_SERVER["REQUEST_URI"],13);

unset($url[0]); if (!$url[1]) $url[1] = 'index';
include_once SITE_FDIR._SL.'engine'.SL.'handle_old_urls.php';

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

include_once SITE_FDIR._SL.'templates'.SL.str_replace('__',SL,$output->template).'.php';
ob_end_flush();
