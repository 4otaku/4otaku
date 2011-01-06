<?

// Быстрый фикс. Обрабатываем запросы на /engine/upload в новом месте
if(!empty($_GET['upload']) && strpos($_GET['upload'], ".") === false && file_exists('engine/upload/'.$_GET['upload'].'.php')) {
    chdir('engine/upload/');
    include $_GET['upload'].'.php';
    exit();
}

include_once 'inc.common.php';

$output_class = 'dynamic__'.$get['m'];
$output = new $output_class();

$func = $get['f'];

$data = $output->$func();
if (isset($output->template) || $data) {
    if (!empty($output->textarea)) ob_end_clean();
    if (isset($output->template)) include_once($output->template);
    else include_once TEMPLATE_DIR.SL.'dinamic'.SL.$get['m'].SL.$get['f'].'.php';
}

if (isset($postparse)) {
	preg_match($postparse, ob_get_clean(), $buffer);
	echo myoutput(end($buffer));
} else {
	ob_end_flush();
}
