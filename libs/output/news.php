<?

class output__news extends engine
{
	public $allowed_url = array(
		array(1 => '|news|', 2 => '|page|', 3 => 'num', 4 => 'end'),
		array(1 => '|news|', 2 => 'any', 3=> '|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|news|', 2 => 'any', 3=> '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		global $url; global $def; global $sets; global $side_modules;
		if ($url[2] && $url[2] != 'page') {
			$return['display'] = array('news','comments');
			$return['news'] = $this->get_news(1,'where (url="'.$url[2].'")');
			$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
			$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
			$return['navi']['all'] = true;
			$return['navi']['name'] = "Страница комментариев";
			$return['navi']['meta'] = $url[2].'/comments/';
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);
		}
		else {
			$return['display'] = array('news','navi');
			$return['navi']['curr'] = max(1,$url[3]);
			$return['news'] = $this->get_news(($return['navi']['curr']-1)*$sets['pp']['news'].', '.$sets['pp']['news'],"");
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from news',2,'count(id)')/$sets['pp']['news']);
			$this->side_modules['top'][] = 'title';
		}
		$return['navi']['base'] = '/news/';
		return $return;
	}

	function get_news($limit, $area) {
		global $error;
		$return = obj::db()->sql('select * from news '.$area.' order by sortdate desc limit '.$limit);
		if (is_array($return)) {
			foreach ($return as $key => $news) {
				$return[$key]['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$news['text']);
			}
			return $return;
		}
		else $error = true;
	}
}
