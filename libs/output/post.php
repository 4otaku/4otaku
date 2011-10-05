<?

class output__post extends engine
{
	function __construct() {
		global $cookie; global $url;
		if (!$cookie) $cookie = new dynamic__cookie();
		$cookie->inner_set('visit.post',time(),false);
		$this->parse_area();
		if (!$url[2]) $this->error_template = 'empty';
	}
	public $allowed_url = array(
		array(1 => '|post|', 2 => '|tag|category|author|language|mixed|', 3=> 'any', 4 => '|page|', 5 => 'num', 6 => 'end'),
		array(1 => '|post|', 2 => '|page|', 3 => 'num', 4 => 'end'),
		array(1 => '|post|', 2 => 'num', 3=> '|show_updates|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|post|', 2 => 'num', 3=> '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end'),
		array(1 => '|post|', 2 => '|updates|', 3 => '|page|', 4 => 'num', 5 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','update','orders','tags'),
		'footer' => array()
	);

	function get_data() {
		global $url; global $def; global $sets;
		$return['navigation'] = $this->get_navigation(array('tag','category','language'));
		if (is_numeric($url[2]) && $url[2]>0) {
			$return['display'] = array('post','comments');
			$return['post'] = $this->get_post(1,'id='.$url[2].' and area != "deleted"');
			$url['area'] = $return['post'][0]['area'];
			$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
			$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
			$return['navi']['all'] = true;
			$return['navi']['name'] = "Страница комментариев";
			$return['navi']['meta'] = $url[2].'/comments/';
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);
			$this->side_modules['top'] = array();
		}
		elseif ($url[2] != 'updates') {
			$return['display'] = array('post','navi');
			if ($url[2] == 'page' || !$url[2]) {
				$area = 'area = "'.$url['area'].'"';
				$return['navi']['curr'] = max(1,$url[3]);
				$return['post'] = $this->get_post(($return['navi']['curr']-1)*$sets['pp']['post'].', '.$sets['pp']['post'],$area);
			}
			elseif ($url[2] == 'tag' || $url[2] == 'category' || $url[2] == 'author' || $url[2] == 'language') {
				$area = 'area = "'.$url['area'].'" and locate("|'.($url['tag'] ? $url['tag'] : mysql_real_escape_string($url[3])).'|",post.'.$url[2].')';
				$return['navi']['curr'] = max(1, $url[5]);
				$return['post'] = $this->get_post(($return['navi']['curr']-1)*$sets['pp']['post'].', '.$sets['pp']['post'],$area);
				$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
				$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);
			}
			elseif ($url[2] == 'mixed') {
				$area = $this->mixed_make_sql($this->mixed_parse($url[3]));
				$return['navi']['curr'] = max(1,$url[5]);
				$return['post'] = $this->get_post(($return['navi']['curr']-1)*$sets['pp']['post'].', '.$sets['pp']['post'],$area);
				$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
			}
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from post where ('.$area.')',2)/$sets['pp']['post']);
		}
		else {
			$return['display'] = array('updates','navi');
			$return['navi']['meta'] = $url[2].'/';
			$return['navi']['curr'] = max(1,$url[4]);
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from updates',2)/$sets['pp']['updates_in_line']);
			$return['updates'] = obj::db()->sql('select updates.*, post.title, post.image, post.comment_count from updates, post where updates.post_id = post.id order by updates.sortdate desc limit '.($return['navi']['curr']-1)*$sets['pp']['updates_in_line'].', '.$sets['pp']['updates_in_line']);
			foreach ($return['updates'] as &$update) {
				$update['link'] = unserialize($update['link']);
				$update['image'] = explode('|',trim($update['image'],'|'));
			}
		}
		$return['navi']['base'] = '/post'.($url['area'] != $def['area'][0] ? '/'.$url['area'] : '').'/';
		return $return;
	}

	function get_post($limit, $area) {
		global $error;
		$return = obj::db()->sql('select * from post where ('.$area.') order by sortdate desc limit '.$limit);
		if (is_array($return)) {
			foreach ($return as &$post) {
				if (trim($post['image'])) $post['image'] = explode('|',$post['image']);
				$post['links'] = unserialize($post['link']);
				$post['files'] = unserialize($post['file']);
				$post['info'] = unserialize($post['info']);
				$post['text'] = preg_replace(array(
					'/(<\/a><\/div><div class="text hidden">)(\s*<br[^>]*>)+/s',
					'/(<br[^>]*>\s*)+(<\/div><\/div>)/s'
					),array('$1','$2'),$post['text']);
			}
			$meta = $this->get_meta($return,array('category','author','language','tag'));
			foreach ($meta as $key => $type) {
				if (is_array($type)) {
					foreach ($return as &$post) {
						foreach ($type as $alias => $name) {
							if (stristr($post[$key],'|'.$alias.'|')) {
								$post['meta'][$key][$alias] = $name;
							}
						}
						foreach ($post['meta'] as &$post_meta) {
							uasort($post_meta, 'transform__array::meta_sort');
						}
					}
				}
			}
			return $return;
		}
		else $error = true;
	}
}
