<?

include_once 'inc.common.php';

$check = new check_values();

include_once 'engine'.SL.'cleanglobals.php';
include_once 'engine'.SL.'metafunctions.php';

$url = explode('/',preg_replace('/\?[^\/]+$/','',$_SERVER["REQUEST_URI"])); 

if(isset($url[0])) unset($url[0]);
if(empty($url[1])) $url[1] = 'index';

include_once 'engine'.SL.'handle_old_urls.php';

if (isset($post['do'])) {
	$post['do'] = explode('.',$post['do']);
	if (count($post['do']) == 2) {
		$input_class = 'input__'.$post['do'][0]; $input = new $input_class;
		$input_function = $post['do'][1]; $input->$input_function($post);		
	}	
	header("HTTP/1.x 302 Moved Temporarily");
	header("Location: http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	exit();	
} else {
	$data = array();

	$output_class = 'output__'.$url[1]; $output = new $output_class;

	$output->check_404($output->allowed_url); 
	if (!$error) 
		$data['main'] = $output->get_data();
		
	$data = array_merge($data,$output->get_side_data($output->side_modules));
	if ($error) 
		$output->make_404($output->error_template);

	include_once TEMPLATE_DIR.SL.str_replace('__',SL,$output->template).'.php';
	ob_end_flush();
}
