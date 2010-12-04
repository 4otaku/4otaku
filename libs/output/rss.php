<? 
include_once('engine'.SL.'engine.php');
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
		global $url; global $db; global $data; global $error; global $check; global $sets;
		if (substr($url[2],0,1) == '=') {
			$alias = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'c' => 'comment', 'o' => 'orders', 'u' => 'updates', 'n' => 'news');
			$types = array_filter(array_unique(str_split(substr($url[2],1))));
			$data = array();
			foreach ($types as $type) 
				if ($type != 'm')
					if (is_array($new_data = $db->sql('select *,"'.$alias[$type].'" as type from '.$alias[$type].' where area="'.($type != 'o' ? 'main' : 'workshop').'" order by sortdate desc limit 0, 30','sortdate')))
						$data = $data + $new_data;
			if($sets['user']['rights'] && array_search('m', $types))				/* Для очередей премодерации */	
				foreach ($types as $type) 
					if ($type != 'o' && $type != 'm')
						if (is_array($new_data = $db->sql('select *,"'.$alias[$type].'" as type from '.$alias[$type].' where area="workshop" order by sortdate desc limit 0, 30','sortdate')))
							$data = $data + $new_data;	
			krsort($data);
			$return['items'] = array_slice($data,0,50,true);
		}
		elseif (is_array($condition = explode('|',_base64_decode($url[2])))) {
			if ($check->lat(implode('',$condition)))
				$return['items'] = $db->sql('select *,"'.$condition[0].'" as type from '.$condition[0].' where area="'.($condition[0] != 'orders' ? 'main' : 'workshop').'" and locate("|'.$condition[2].'|",'.$condition[1].') order by sortdate desc limit 0, 20','sortdate');
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
			/*$item['text'] = $this->replace_spoilers($item['text']);*/
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
		if (trim($item['image'])) $item['image'] = explode('|',$item['image']);
		if ($item['area'] == 'workshop') $item['title'] = '(Очередь премодерации)' . $item['title'];
		$item['links'] = unserialize($item['link']);
		$item['files'] = unserialize($item['file']);
		$item['info'] = unserialize($item['info']);
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/post/'.$item['id'].'/';		
		$item['text'] = str_replace('href="/go?','href="',$item['text']);		
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);	
		$item['comments_link'] = $item['rss_link'].'comments/all/';
		return $item;
	}
	
	function convert_video($item) {
		global $sets;
		$item['title'] = 'Новое видео: ' . $item['title'];
		if ($item['area'] == 'workshop') $item['title'] = '(Очередь премодерации)' . $item['title'];
		$item['object'] = str_replace(array('%video_width%','%video_height%'),$sets['video']['thumb'],$item['object']);	
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/video/'.$item['id'].'/';
		$item['text'] =  str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);		
		$item['comments_link'] = $item['rss_link'].'comments/all/';	
		return $item;
	}
	
	function convert_art($item) {
		$meta = $this->get_meta(array($item),array('category','author','tag'));
		foreach ($meta as $key => $type) 
			if (is_array($type))
				foreach ($type as $alias => $name) 
					if (stristr($item[$key],'|'.$alias.'|')) 
						$item['meta'][$key][$alias] = $name;
		$item['title'] = 'Изображение №'.$item['id'];
		if ($item['area'] == 'workshop') $item['title'] = '(Очередь премодерации)' . $item['title'];
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/art/'.$item['id'].'/';
		$item['comments_link'] = $item['rss_link'].'comments/all/';	
		return $item;
	}
	
	function convert_comment($item) {
		global $db;
		if ($item['place'] == 'art') {
		$item['title'] = 'Комментарий к изображению №'.$db->sql('select id from '.$item['place'].' where id='.$item['post_id'],2);
		$item['preview_picture'] = $db->sql('select thumb from '.$item['place'].' where id='.$item['post_id'],2);
		}
		else if ($item['place'] == 'video') $item['title'] = 'Комментарий к видео "'.$db->sql('select title from '.$item['place'].' where id='.$item['post_id'],2).'"';
		else if ($item['place'] != 'news') $item['title'] = 'Комментарий к записи "'.$db->sql('select title from '.$item['place'].' where id='.$item['post_id'],2).'"';
		else $item['title'] = 'Комментарий к записи "'.$db->sql('select title from '.$item['place'].' where url="'.$item['post_id'].'"',2).'"';
		if ($item['place'] == 'orders') $item['place'] = 'order';
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.$item['place'].'/'.$item['post_id'].'/';		
		$item['text'] = str_replace('href="/go?','href="',$item['text']);
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['comments_link'] = $item['rss_link'].'comments/all/';	
		return $item;
	}
	
	function convert_updates($item) {
		global $db;	
		$item['title'] = 'Обновление записи '.$db->sql('select title from post where id='.$item['post_id'],2);
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/post/'.$item['post_id'].'/show_updates/';		
		$item['link'] = unserialize($item['link']);
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
		if (trim($item['image'])) $item['image'] = current(explode('|',$item['image']));
		$item['rss_link'] = 'http://'.$_SERVER['HTTP_HOST'].'/news/'.$item['url'].'/';
		$item['text'] = str_replace('href="/go?','href="',$item['text']);	
		$item['text'] = $this->replace_spoilers($item['text'],$item['rss_link']);
		$item['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$item['text']);		
		$item['comments_link'] = $item['rss_link'].'comments/all/';			
		return $item;
	}
	
	private function replace_spoilers($text, $link)	{
		return preg_replace('/<div\sclass="mini-shell"><div\sclass="handler"\swidth="100%"><span\sclass="sign">.<\/span>\s<a\shref="#"\sclass="disabled">([^<]*)<\/a><\/div><div\sclass="text\shidden">.*<\/div><\/div>/suiU','<a href="'.$link.'">$1</a> <br />',$text);
	/*	$startTag 	= '<span class="sign">↓</span> <a href="#" class="disabled">';
		$endTag 	= '</div></div>';
		$return 	= substr_replace($workString, '->Spoiler here<-', strpos($workString, $startTag), (strrpos($workString, $endTag) - strpos($workString, $startTag)) + strlen($endTag));
		return $return; */
		/*
		$startTag 	= '<span class="sign">↓</span> <a href="#" class="disabled">';
		$endTag 	= '</div></div>';
		if(preg_replace($startTag."/[.*]+/".endTag,"-->Spoiler Here<--",,,))
		{
		
		}
		*/
	}
}
