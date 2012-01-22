<?

include_once 'inc.common.php';

$output_class = 'dynamic__'.query::$get['m'];
$output = new $output_class();
$func = query::$get['f'];
$data = $output->$func();
if (isset($output->template) || $data) {
	if (!empty($output->textarea)) {
		ob_end_clean();
	}

	$url = query::$url;

	if (isset($output->template)) {
		include_once($output->template);
	} else {
		include_once TEMPLATE_DIR.SL.'dinamic'.SL.query::$get['m'].SL.query::$get['f'].'.php';
	}
}

if (!empty($output->postparse)) {
	preg_match($output->postparse, ob_get_clean(), $buffer);
	echo myoutput(end($buffer));
} else {
	ob_end_flush();
}
