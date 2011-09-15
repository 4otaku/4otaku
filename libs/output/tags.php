<?

class output__tags extends engine
{
	function __construct() {
		global $def;
		$this->allowed_url[0][2] = '|'.implode('|',$def['type']).'|';
		$this->allowed_url[0][3] = '|'.$def['area'][0].'|'.$def['area'][2].'|';
	}
	public $allowed_url = array(
		array(1 => '|tags|', 2 => '', 3 => '', 4 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		global $sets; global $def; global $url;
		$return['display'] = array('tags');
		if (!$url[2]) $url[2] = $def['type'][0];
		if ($url['3'] != $def['area'][0] && $url['3'] != $def['area'][2]) $area = $url[2].'_'.$def['area'][0];
		else $area = $url[2].'_'.$url['3'];

		$words = array(
			$def['type'][0] => array('запись','записей','записи'),
			$def['type'][1] => array('видео','видео','видео'),
			$def['type'][2] => array('арт','арта','артов')
		);

		$return['tags'] = $this->tag_cloud(30,10,$area,$words[$url[2]]);
		return $return;
	}

}
