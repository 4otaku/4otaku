<?

class output__rss extends engine
{

	function __construct() {
		global $url; global $sets;
		if (!$url[2]) $url[2] = '='.$sets['rss']['default'];
	}
	public $allowed_url = array(
		array(1 => '|rss|', 2 => 'any', 3=> 'end')
	);
	public $template = 'rss';
	public $error_template = 'rss';
	public $side_modules = array(	);

	function get_data() {
		global $url; global $data; global $error; global $check; global $sets;
		if (substr($url[2],0,1) == '=') {
			$alias = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'c' => 'comment', 'o' => 'orders', 'u' => 'post_update', 'n' => 'news');
			$types = array_filter(array_unique(str_split(substr($url[2],1))));
			$data = array();
			foreach ($types as $type) {
				if ($type != 'm') {
					if (is_array($new_data = obj::db()->sql('select *,"'.$alias[$type].'" as type from '.$alias[$type].' where area="'.($type != 'o' ? 'main' : 'workshop').'" order by sortdate desc limit 0, 30','sortdate'))) {
						$data = $data + $new_data;
					}
				}
			}
			/* Для очередей премодерации */
			if (array_search('m', $types)) {
				foreach ($types as $type) {
					if ($type != 'o' && $type != 'm') {
						if (is_array($new_data = obj::db()->sql('select *,"'.$alias[$type].'" as type from '.$alias[$type].' where area="workshop" order by sortdate desc limit 0, 30','sortdate'))) {
							$data = $data + $new_data;
						}
					}
				}
			}
			krsort($data);
			$return['items'] = array_slice($data,0,50,true);
		}
		elseif (is_array($condition = explode('|',_base64_decode($url[2])))) {
			if ($check->lat(implode('',$condition)))
				$return['items'] = obj::db()->sql('select *,"'.$condition[0].'" as type from '.$condition[0].' where area="'.($condition[0] != 'orders' ? 'main' : 'workshop').'" and locate("|'.$condition[2].'|",'.$condition[1].') order by sortdate desc limit 0, 20','sortdate');
			else {
				$error = true;
				return '';
			}
		}
		else {
			$error = true;
			return '';
		}
		foreach ($return['items'] as &$item) {
			$function = 'convert_'.$item['type'];
			$item = $this->$function($item);
			$item['rss_title'] = html_entity_decode($item['title']);
			$item['guid'] = $alias[$type].'-'.$item['id'];
		}
		reset($return['items']);
		$data['feed']['domain'] = 'http://'.$_SERVER['HTTP_HOST'];
		$return['lastbuild'] = ceil(key($return['items'])/1000);
		$return['navi']['base'] = $data['feed']['domain'].'/art/'; // Используется только в шаблоне для арта, поэтому и такое значение
		return $return;
	}

	function convert_post($item) {
		$worker = new Read_Post();
		$worker->get_item($item['id']);
		$post = $worker->get_data('items');
		$item = current($post);
		$item['type'] = 'post';

		$item['rss_base'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/post/'.$item['id'].'/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	function convert_video($item) {

		$worker = new Read_Video();
		$worker->get_item($item['id']);
		$video = $worker->get_data('items');
		$item = current($video);
		$item->set_display_object('thumb');
		$item['type'] = 'video';

		$item['title'] = 'Новое видео: ' . $item['title'];
		if ($item['area'] == 'workshop') $item['title'] = '(Очередь премодерации)' . $item['title'];

		$item['rss_base'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/video/'.$item['id'].'/';
		$item['text'] =  str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	function convert_art($item) {
		$meta = $this->get_meta(array($item),array('category','author','tag'));
		foreach ($meta as $key => $type) {
			if (is_array($type)) {
				foreach ($type as $alias => $name) {
					if (stristr($item[$key],'|'.$alias.'|')) {
						$item['meta'][$key][$alias] = $name;
					}
				}
			}
		}
		$item['title'] = 'Изображение №'.$item['id'];
		if ($item['area'] == 'workshop') $item['title'] = '(Очередь премодерации)' . $item['title'];
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/art/'.$item['id'].'/';
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	function convert_comment($item) {
		if ($item['place'] == 'art') {
			$item['title'] = 'Комментарий к изображению №'.$item['post_id'];
			$item['preview_picture'] = obj::db()->sql('select thumb from '.$item['place'].' where id='.$item['post_id'],2);
		} elseif ($item['place'] == 'video') {
			$item['title'] = 'Комментарий к видео "'.obj::db()->sql('select title from '.$item['place'].' where id='.$item['post_id'],2).'"';
		} elseif ($item['place'] != 'news') {
			$item['title'] = 'Комментарий к записи "'.obj::db()->sql('select title from '.$item['place'].' where id='.$item['post_id'],2).'"';
		} else {
			$item['title'] = 'Комментарий к записи "'.obj::db()->sql('select title from '.$item['place'].' where url="'.$item['post_id'].'"',2).'"';
		}

		if ($item['place'] == 'orders') $item['place'] = 'order';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.$item['place'].'/'.$item['post_id'].'/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	function convert_post_update($item) {
		$worker = new Read_Post_Update();
		$item = $worker->get_item($item['id']);

		$item['type'] = 'update';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/post/'.$item['post_id'].'/show_updates/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/post/'.$item['post_id'].'/comments/all/';
		return $item;
	}

	function convert_orders($item) {
		$item['title'] = 'Заказ: '.$item['title'];
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/order/'.$item['id'].'/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	function convert_news($item) {
		$worker = new Read_News();
		$worker->get_item($item['id']);
		$news = $worker->get_data('items');
		$item = current($news);
		$item['type'] = 'news';

		$item['rss_base'] = 'http://'.$_SERVER['HTTP_HOST'].'/';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/news/'.$item['url'].'/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$item['text']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}

	private function replace_spoilers($text, $link)	{
		return preg_replace('/<div\sclass="mini-shell"><div\sclass="handler"\swidth="100%"><span\sclass="sign">.<\/span>\s<a\shref="#"\sclass="disabled">([^<]*)<\/a><\/div><div\sclass="text\shidden">.*<\/div><\/div>/suiU','<a href="'.$link.'">$1</a> <br />',$text);
	}
}
