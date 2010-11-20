<? 
include_once(SITE_FDIR.SL.'engine'.SL.'engine.php');
class output__test extends engine
{
	public $allowed_url = array(
		array(1 => '|test|', 2 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'header' => array('top_buttons'),
		'top' => array('add_bar'),
		'sidebar' => array('test2','test'),
		'footer' => array()
	);
	
	function get_data() {
		global $url; global $db;
		//$return['display'] = array('test');
	/*	$main_tags = $db->sql('select alias, name, post_main from tag where alias != "18" order by post_main desc limit 10','post_main');
		natsort($main_tags);		$main_tags = array_reverse($main_tags,true);
		$tagsme  = $db->sql('select alias, name from tag','alias');
		$posts  = $db->sql('select id,tag from post','id');
		foreach ($main_tags as &$main_tag)
			foreach ($posts as $post) if (stristr($post,'|'.$main_tag['alias'].'|')) {
				$tags = explode('|',trim($post,'|'));
				foreach ($tags as $tag) if ($tag != $main_tag['alias'] && $tag != "18") $main_tag['lesser'][$tag]++;
			}
		foreach ($main_tags as &$main_tag) arsort($main_tag['lesser']);
		$return['alias'] = $tagsme; $return['tags'] = $main_tags; */

		return $return;
	}
	
}
