<?

include_once 'inc.common.php';

$_SERVER["REQUEST_URI"] = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/','',$_SERVER["REQUEST_URI"]);
$url = explode('/',preg_replace('/\?[^\/]+$/','',$_SERVER["REQUEST_URI"])); 

if(isset($url[0])) unset($url[0]);
if(empty($url[1])) $url[1] = 'index';

include_once ROOT_DIR.SL.'engine'.SL.'handle_old_urls.php';

if (isset(query::$post['do'])) {
	query::$post['do'] = explode('.',query::$post['do']);
	if (count(query::$post['do']) == 2) {
		$input_class = 'input__'.query::$post['do'][0]; $input = new $input_class;
		$input_function = query::$post['do'][1]; $input->$input_function(query::$post);		
	}	
	$redirect = 'http://'.def::site('domain') . (empty($input->redirect) ? $_SERVER["REQUEST_URI"] : $input->redirect);
	engine::redirect($redirect);
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
