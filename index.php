<?

include_once 'inc.common.php';

$request = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/', '', $_SERVER["REQUEST_URI"]);
$request = urldecode($request);
$request = preg_replace('/\/tag\/([^\/\p{Cyrillic}\p{Hiragana}\p{Katakana}]*)/eui', '"/tag/".urlencode("$1")', $request);

$url = explode('/', preg_replace('/\?[^\/]+$/', '', $request));

if (isset($url[0])) {
	unset($url[0]);
}
if (empty($url[1])) {
	$url[1] = 'index';
}

if (preg_match('/[^a-z\d_]/ui', $url[1])) {
	include_once TEMPLATE_DIR.SL.'404'.SL.'fatal.php';
	ob_end_flush();
	exit();
}

query::$url = $url;

include_once ROOT_DIR.SL.'engine'.SL.'handle_old_urls.php';

if ($url[1] == 'confirm' || $url[1] == 'stop_emails') {
	if ($url[1] == 'confirm') {
		input__comment::subscribe_comments(
			decrypt($url[2]),
			$url[3],
			$url[5] ? $url[4].'|'.$url[5] : null,
			$url[5] ? null: $url[4]
		);
	} else {
		input__comment::add_to_black_list(decrypt($url[2]));
	}
	$redirect = 'http://'.def::site('domain').'/'.(empty($url[3]) ? 'news/' : $url[3].'/'.$url[4].'/'.$url[5]);
	engine::redirect($redirect);
}

if (isset(query::$post['do'])) {
	query::$post['do'] = explode('.', query::$post['do']);
	if (count(query::$post['do']) == 2) {
		$input_class = 'input__'.query::$post['do'][0];
		$input = new $input_class;
		$input_function = query::$post['do'][1];
		$input->$input_function(query::$post);
	}
	$redirect = 'http://'.def::site('domain').(empty($input->redirect) ? $_SERVER["REQUEST_URI"] : $input->redirect);
	engine::redirect($redirect);
} elseif (isset(query::$post['action']) &&
	in_array(query::$post['action'], array('Create', 'Update', 'Delete'))) {

	$class = query::$post['action'] . '_' . ucfirst($url[1]);

	if (class_exists($class)) {

		$worker = new $class();

		$function = empty(query::$post['function']) ?
			'main' : query::$post['function'];

		if ($worker->check_access($function, query::$post)) {

			$worker->$function();
		}

		$worker->check_redirect();
	}
} else {

	$class = 'Read_' . ucfirst($url[1]);

	if (class_exists($class)) {

		$worker = new $class();

		$worker->process(query::$url);
	} else {

		$data = array();
		
		$output_class = 'output__'.$url[1];
		$output = new $output_class;

		$output->check_404($output->allowed_url);
		if (!$error) {
			$data['main'] = $output->get_data();
		}

		$data = array_merge($data, $output->get_side_data($output->side_modules));
		if ($error) {
			$output->make_404($output->error_template);
		}

		include_once TEMPLATE_DIR.SL.str_replace('__',SL,$output->template).'.php';
	}
	
	ob_end_flush();
}
