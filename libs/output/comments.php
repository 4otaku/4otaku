<?

class output__comments extends engine
{
	function __construct() {
		global $def; global $url;
		$area = '|'.implode('|',$def['type']).'|orders|news|';
		$this->allowed_url[0][2] = $area;
		if ($url[2] == "order") $url[2] = "orders";
	}
	public $allowed_url = array(
		array(1 => '|comments|', 2 => '', 3=> '|page|', 4 => 'num', 5 => 'end'),
		array(1 => '|comments|', 2 => '|page|', 3 => 'num', 4 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		global $url; global $def; global $sets;
		$return['display'] = array('commentline', 'navi');
		if ($url[2] == 'page' || !$url[2]) {
			$return['navi']['curr'] = max(1,$url[3]);
			$return['blocks'] = $this->get_comments(($return['navi']['curr']-1)*$sets['pp']['comment_in_line'].', '.$sets['pp']['comment_in_line'],'');
			$return['navi']['last'] = ceil(count(obj::db()->sql('select id from comment where area != "deleted" group by place, post_id'))/$sets['pp']['comment_in_line']);
		}
		else {
			$return['navi']['curr'] = max(1,$url[4]);
			$return['blocks'] = $this->get_comments(($return['navi']['curr']-1)*$sets['pp']['comment_in_line'].', '.$sets['pp']['comment_in_line'],' and place="'.$url[2].'"');
			$return['navi']['last'] = ceil(count(obj::db()->sql('select id from comment where area != "deleted" and place="'.$url[2].'" group by place, post_id'))/$sets['pp']['comment_in_line']);
			$return['navi']['meta'] = $url[2].'/';
		}
		$return['navi']['start'] = max($return['navi']['curr']-5,2);
		$return['navi']['base'] = '/comments/';
		if ($url[2] == "orders") $url[2] = "order";
		return $return;
	}

	function get_comments($limit, $area) {
		global $error; global $def;
		$return = obj::db()->sql('select place, post_id from comment where (area != "deleted"'.$area.') group by place, post_id order by max(sortdate) desc limit '.$limit);
		if (is_array($return)) {
			$select = array(
				$def['type'][0] => "id, comment_count, title, last_comment",
				$def['type'][1] => "id, comment_count, title, id as image, last_comment",
				$def['type'][2] => "id, comment_count, id as title, thumb as image, last_comment",
				"orders" => "id, comment_count, title, email as image, last_comment",
				"news" => "id, comment_count, title, image, last_comment"
			);
			foreach ($return as $one) {
				$queries[$one['place']] .= '" or id="'.$one['post_id'];
				$comment_query .= ' or (post_id="'.$one['post_id'].'" and place="'.$one['place'].'")';
			}
			$return = array();
			foreach ($queries as $key => $query) {
				if ($_comments = obj::db()->sql('select '.$select[$key].', "'.$key.'" as "place" from '.$key.' where ('.substr($query,5).'")','last_comment')) {
					$return = $return + $_comments;
				}
			}
			krsort($return);
			$comments = obj::db()->sql('select * from comment where (('.substr($comment_query,4).') and area != "deleted") order by sortdate');
			foreach ($return as &$one) {
				foreach ($comments as $comment)
					if ($comment['post_id'] == $one['id'] && $comment['place'] == $one['place'])
						$one['comments'][] = $comment;
				switch ($one['place']) {
					case $def['type'][0]:
						$image = Database::order('order', 'asc')
							->get_field('post_image', 'file', 'post_id = ?', $one['id']);

						$one['image'] = '/images/post/thumb/'.$image.'.jpg';
						break;
					case $def['type'][1]:
						$one['image'] = 'http://www.gravatar.com/avatar/'.md5(strtolower($one['image'])).'?s=100&d=identicon&r=G';
						$one['title'] = "Видео: ".$one['title'];
						break;
					case $def['type'][2]:
						$one['image'] = '/images/booru/thumbs/'.$one['image'].'.jpg';
						$one['title'] = "Изображение №".$one['title'];
						break;
					case "orders":
						$one['image'] = 'http://www.gravatar.com/avatar/'.md5(strtolower($one['image'])).'?s=100&d=identicon&r=G';
						$one['title'] = "Заказ: ".$one['title'];
						break;
					case "news":
						$one['image'] = '/images/news/thumb/'.$one['image'].'.jpg';
						break;
				}
			}

			return $return;
		}
		else $error = true;
	}
}
