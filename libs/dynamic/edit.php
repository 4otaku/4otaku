<? 

class dynamic__edit extends engine
{
	public $template;	
	public $textarea = false;	
	
	function show() {
		global $get; global $postparse; global $url;
		$url = array_filter(explode('/',preg_replace('/\?[^\/]+$/','',$get['path']))); unset($url[0]); if (!$url[1]) $url[1] = 'index';
		$output = 'output__'.$get['type'];
		$output = new $output;
		if ($get['type'] == 'video') 
			if ($get['num']) $third = sets::video('full'); else $third = sets::video('thumb');
		switch ($get['type']) {
			case 'post': 
				$data['main']['post'] = $output->get_post(1,'id='.$get['id']); 
				$this->template = 'templates/main/post.php'; 
				break;
			case 'video': 
				$data['main']['video'] = $output->get_video(1,'id='.$get['id'],$third); 
				$this->template = 'templates/main/video.php'; 
				break;
			case 'order': 
				$data['main'] = $output->order_single($get['id']); 
				$this->template = 'templates/main/order/single.php'; 
				break;
			case 'art': 
				$data['main']['art'] = $output->get_art(1,'id='.$get['id']); 
				$this->template = 'templates/main/booru/single.php'; 
				break;
			default: die;
		}

		$postparse = '/<div[^>]*class="innerwrap[^"]*"[^>]*>.*<\/div><!-- wrapend -->/uis';
		if ($get['num']) $data['main']['display']['comments'] = true; else $data['main']['display'] = array();
		return $data;
	}

	function save() {
		global $post;
		$input = 'input__'.$post['type']; $func = 'edit_'.$post['part'];
		$input = new $input;
		if ($post['type'] == 'order') $post['type'] = 'orders';
		$old_data = obj::db()->sql('select * from '.$post['type'].' where id='.$post['id'],1);
		$input->$func();
		$new_data = obj::db()->sql('select * from '.$post['type'].' where id='.$post['id'],1);
		if ($old_data != $new_data) {
			unset($new_data['id']);
			obj::db()->sql('update search set lastupdate=0 where place="'.$post['type'].'" and item_id="'.$post['id'].'"',0);
			if ($post['type'] == 'orders') $post['type'] = 'order';
			obj::db()->insert('versions',array($post['type'],$post['id'],base64_encode(serialize($new_data)),ceil(microtime(true)*1000),sets::user('name'),$_SERVER['REMOTE_ADDR']));
		}
	}
	
	function remove_from_pool() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->num($get['val'])) {
			obj::db()->sql('update art_pool set count = count - 1, art = replace(art,"|'.$get['val'].'|","|") where (id="'.$get['id'].'" and (password="" or password="'.md5($get['password']).'"))',0);
			obj::db()->sql('update art set pool = replace(pool,"|'.$get['id'].'|","|") where id="'.$get['val'].'"',0);			
		}
	}	
	
	function sort_pool() {
		global $get; global $post; global $check;
		if ($check->num($get['id'])) {
			$post['art'] = '|'.implode('|',array_reverse($post['art'])).'|';
			obj::db()->sql('update art_pool set art = "'.$post['art'].'" where (id="'.$get['id'].'" and (password="" or password="'.md5($get['password']).'"))',0);
		}
	}	

	function title() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->lat($get['type'])) 
			return array('value' => obj::db()->sql('select title from '.$get['type'].' where id='.$get['id'],2));
	}
	
	function text() {
		global $get; global $check;
		$this->textarea = true;
		if ($check->num($get['id']) && $check->lat($get['type'])) 
			return array('value' => obj::db()->sql('select pretty_text from '.$get['type'].' where id='.$get['id'],2));
	}

	function post_images() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'post') 
			return array('value' => array_filter(explode('|',obj::db()->sql('select image from post where id='.$get['id'],2))));
	}	
	
	function post_links() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'post') 
			return array('value' => unserialize(obj::db()->sql('select link from post where id='.$get['id'],2)));
	}	
	
	function post_bonus_links() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'post') {
			$value = unserialize(obj::db()->sql('select info from post where id='.$get['id'],2));
			$value ? null : $value = array(false);
			return array('value' => $value);
		}
	}	
	
	function post_files() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'post') 
			return array('value' => unserialize(obj::db()->sql('select file from post where id='.$get['id'],2)));
	}
	
	function video_link() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'video') 
			return array('value' => obj::db()->sql('select link from video where id='.$get['id'],2));
	}	
	
	function category() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->lat($get['type'])) 
			return array('value' => array_filter(explode('|',obj::db()->sql('select category from '.$get['type'].' where id='.$get['id'],2))),
						'categories' => obj::db()->sql('select name, alias from category'.($get['type'] != 'orders' ? ' where locate("|'.$get['type'].'|",area)' : '').' order by id','alias'));
	}	
	
	function language() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->lat($get['type'])) 
			return array('value' => array_filter(explode('|',obj::db()->sql('select language from '.$get['type'].' where id='.$get['id'],2))),
						'languages' => obj::db()->sql('select name, alias from language order by id','alias'));			
	}
	
	function tag() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->lat($get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select tag from '.$get['type'].' where id='.$get['id'],2))));
			$meta = obj::db()->sql('select alias, name from tag where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) if ($meta[$one]) $one = $meta[$one];
		}
		return $return;
	}			
	
	function author() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->lat($get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select author from '.$get['type'].' where id='.$get['id'],2))));
			$meta = obj::db()->sql('select alias, name from author where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) if ($meta[$one]) $one = $meta[$one];
		}
		return $return;
	}

	function orders_username() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'orders') 
			return array('value' => obj::db()->sql('select username from orders where id='.$get['id'],2));
	}
	
	function orders_mail() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'orders') 
			return array('value' => obj::db()->sql('select email, spam from orders where id='.$get['id'],1));
	}	
	
	function art_source() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'art') 
			return array('value' => obj::db()->sql('select source from art where id='.$get['id'],2));
	}		
	
	function art_image() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'art') 
			return true;
	}		
	
	function art_groups() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'art') {
			$return = obj::db()->sql('select id, name from art_pool','id');
			asort($return);
			return $return;			
		}
	}	
		
	function art_translations() {
		global $get; global $check;
		if ($check->num($get['id']) && $get['type'] == 'art') 
			return true;
	}	
		
	function comment() {
		global $get; global $check;
		if ($check->num($get['id']) && $check->rights()) 
			return obj::db()->sql('select pretty_text,username from comment where id='.$get['id'],1);
	}			
}
