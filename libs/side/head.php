<?

class Side_Head
{
	private $types = array(
		'tag' => 'Тэг',
		'category' => 'Категория',
		'author' => 'Автор',
		'language' => 'Язык'
	);

	protected $js = array(
		'jquery-1.6.2.min.js', 'plugins.js', 'main.js'
	);

	protected $css = array(
		'plugins.css', 'main.css', 'header.css'
	);

	function title() {
		$url = query::$url;
		$func = 'title_'.$url[1];
		if (method_exists($this, $func) && $return = $this->$func($url)) {
			return def::site('short_name') . ' ' . $return;
		}

		return def::site('name');
	}

	public function js() {
		$return = $this->get_jss_data($this->js, 'admin.js');

		$plugins = sets::array_get('plugins');

		$ids = array_keys($plugins);
		$names = Database::get_vector('plugin', array('id', 'filename'),
			Database::array_in('id', $ids), $ids);

		foreach($plugins as $id => $on) {
			if (!empty($on)) {
				$return['list'][] = 'plugin/' . $names[$id] . '.js';
			}
		}

		return $return;
	}

	public function css() {
		return $this->get_jss_data($this->css, 'admin.css');
	}

	protected function get_jss_data($files, $admin_files) {
		$admin_files = (array) $admin_files;

		if (sets::user('rights')) {
			$files = array_merge($files, $admin_files);
		}

		$a = microtime(true);
		$mtime = 0;
		foreach ($files as $file) {
			$path = ROOT_DIR.SL.'jss'.SL.$file;

			$mtime = max($mtime, filemtime($path));
		}

		return array(
			'list' => $files,
			'date' => $mtime,
		);
	}

	function title_art() {
		global $url; global $data;
		if (is_numeric($url[2])) {
			$return = ' -';
			$cat = $data['main']['art'][0]['meta']['category'];
			unset($cat['none']);
			if (count($cat)) {
				foreach ($cat as $one) $return .= ' '.$one;
				$return .= ': ';
			}
			if (is_array($data['main']['art'][0]['meta']['tag']))
				foreach ($data['main']['art'][0]['meta']['tag'] as $tag)
					$return .= ' '.$tag['name'];
		}
		elseif ($url[2] == 'mixed') {
			$return = '. Просмотр сложной выборки.';
		}
		elseif ($url[2] == 'date') {
			$return = '. Просмотр по дате.';
		}
		elseif ($url[2] == 'pool') {
			if (!is_numeric($url[3])) $return = '. Просмотр списка групп.';
			else $return = '. Группа: '.$data['main']['pool']['name'];
		}
		else {
			$types = $this->types;
			if ($types[$url[2]] && $name = obj::db()->sql('select name from '.$url[2].' where alias="'.$url[3].'"',2))
				$return = '. '.$types[$url[2]].': '.$name;
		}
		return 'Арт'.$return;
	}

	function title_video() {
		global $url; global $data;
		if (is_numeric($url[2])) {
			$return = 'Видео: '.$data['main']['video'][0]['title'];
		}
		elseif ($url[2] == 'mixed') {
			$return = 'Видео. Просмотр сложной выборки.';
		}
		elseif ($url[2] == 'date') {
			$return = 'Видео. Просмотр по дате.';
		}
		else {
			$types = $this->types;
			if ($types[$url[2]] && $name = obj::db()->sql('select name from '.$url[2].' where alias="'.$url[3].'"',2))
				$return = 'Видео. '.$types[$url[2]].': '.$name;
		}
		if (!$return) return $this->def['site']['name'];
		return $return;
	}

	function title_news() {
		global $url;
		if ($url[2])
			return $this->def['site']['short_name'] . ' '.obj::db()->sql('select title from news where url="'.$url[2].'"',2);
		else
			return $this->def['site']['name'];
	}

	function title_logs() {
		global $data; global $url;

		if (isset($data['main']['navi']['today']['name'])) {
			return $this->def['site']['short_name'] . ' Логи за '.$data['main']['navi']['today']['name'].', '.$url[2];
		}

		return  $this->def['site']['short_name'].' Поиск по логам фразы "'.urldecode($url[3]).'"';
	}

	function title_archive() {
		return $this->def['site']['short_name'] . ' Архив.';
	}

	function title_comments() {
		return $this->def['site']['short_name'] . ' Лента комментариев.';
	}

	function title_gouf() {
		return $this->def['site']['short_name'] . ' Gouf Custom MS-07B-3.';
	}

	function title_tags() {
		return $this->def['site']['short_name'] . ' Облако тегов.';
	}

	function title_order() {
		global $data; global $url;
		if (is_numeric($url[2])) return $this->def['site']['short_name'] . ' Заказ: '.$data['main']['order_single']['title'];
		return $this->def['site']['short_name'] . ' Стол заказов.';
	}
}
