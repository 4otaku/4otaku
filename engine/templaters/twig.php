<?

	include_once 'Twig'.SL.'Autoloader.php';
	
	Twig_Autoloader::register();

	function twig_load_template($template_type, $template, $params) {
		$loader = new Twig_Loader_Filesystem(ROOT.SL.'templates');
		
		$twig = new Twig_Environment($loader, array(
		  'cache' => ROOT.SL.'cache',
		  'auto_reload' => true
		));
	
		$template = $twig->loadTemplate($template_type.SL.$template.'.html');
		
		$template->display($params);
	}
