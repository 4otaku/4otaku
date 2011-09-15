<?

class output__soku extends engine
{
	public $allowed_url = array(
		array(1 => '|soku|', 2 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		$return['display'] = array('soku_list', 'soku_replays');

		return $return;
	}
}
