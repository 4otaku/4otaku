<?

	include_once ROOT_DIR.SL.'engine'.SL.'external'.SL.'Twig'.SL.'Autoloader.php';

	spl_autoload_register(array(new Twig_Autoloader, 'autoload'), true, true);

	function twig_load_template($template, $params) {		
		$twig = get_twig();
		
		$params['data'] = $params;
		$params['_get'] = query::$get;
		$params['_post'] = query::$post;
		$params['_sets'] = sets::$data;
		$params['_def'] = def::$data;

		$template = $twig->loadTemplate($template.'.html');

		$template->display($params);
	}

	function get_twig() {
		global $twig;

		if (empty($twig)) {
			$twig_loader = new Twig_Loader_Filesystem(ROOT_DIR.SL.'templates_twig');

			$twig = new Twig_Environment($twig_loader, array(
			  'cache' => ROOT_DIR.SL.'files'.SL.'twig_cache',
			  'auto_reload' => true,
			  'autoescape' => false
			));
		}

		return $twig;
	}
