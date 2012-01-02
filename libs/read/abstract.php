<?php

abstract class Read_Abstract
{
	protected $minimal_rights = 0;
	protected $url_rights = array();

	protected $template;
	protected $error_template;

	protected $side_modules = array();

	protected $data = array();

	protected $page = 1;
	protected $per_page = 1;
	protected $count = 0;

	public function __construct() {}

	public function process($url) {
		if (!$this->check_access($url)) {
			$this->error_headers();
			$this->do_output($this->error_template);
		}

		$function = $this->get_function($url);

		if (method_exists($this, $function)) {
			$this->$function($url);
		} else {
			$this->error_headers();
			$this->do_output($this->error_template);
		}

		$this->do_output($this->template, $this->data);
	}

	protected function check_access($url) {
		if (sets::user('rights') < $this->minimal_rights) {
			return false;
		}

		$function = $this->get_function($url);

		if (
			array_key_exists($function, $this->url_rights) &&
			sets::user('rights') < $this->function_rights[$function]
		) {
			return false;
		}

		return true;
	}

	protected function get_function($url) {

		if (empty($url[1])) {
			return 'display_index';
		}

		if (ctype_digit($url[1])) {
			return 'display_single_item';
		}

		return 'display_' . $url[1];
	}

	protected function get_side_data ($input) {
		$return = array();

		foreach ($input as $part => $modules) {
			$class = 'Side_' . ucfirst($part);
			$worker = new $class();
			foreach ($modules as $module) {
				$return[$part][$module] = $worker->$module();
			}
		}

		return $return;
	}

	protected function set_page($url, $index) {
		if (empty($url[$index])) {
			return;
		}
		if ($url[$index] > 0) {

			$this->page = (int) $url[$index];
		} elseif ($url[$index] == 'all') {

			$this->page = $url[$index];
		}
	}

	protected function do_output($template, $data = array()) {

		$data['sub'] = $this->get_side_data($this->side_modules);

		$data['title'] = $this->get_title();

		twig_load_template($template, $data);
		exit();
	}

	protected function error_headers() {

		$date = gmdate("D, d M Y H:i:s");

		header("Expires: $date GMT");
		header("Last-Modified: $date GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("HTTP/1.x 404 Not Found");
	}

	protected function get_title() {
		return def::site('name');
	}

	// @TODO: хак для поиска и RSS, удалить при возможности
	public function get_data($part = false) {

		if (empty($part)) {
			return $this->data;
		}

		return $this->data[$part];
	}

	protected function get_bottom_navi($base) {
		$return = array();

		$return['curr'] = $this->page;

		$return['last'] = ceil($this->count / $this->per_page);

		$return['start'] = max($return['curr'] - 5, 2);
		$return['end'] = min($return['curr'] + 6, $return['last'] - 1);

		$return['base'] = $base;

		return $return;
	}

	protected function get_place() {
		$class = get_called_class();

		$class = explode('_', $class);
		array_shift($class);

		return strtolower(reset($class));
	}
}
