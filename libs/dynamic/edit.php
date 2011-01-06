<? 

class dynamic__edit extends engine
{
	public $template;	
	public $textarea = false;	
	
	function show() {
		global $postparse; global $url;
		$url = array_filter(explode('/',preg_replace('/\?[^\/]+$/','',query::$get['path']))); unset($url[0]); if (!$url[1]) $url[1] = 'index';
		$output = 'output__'.query::$get['type'];
		$output = new $output;
		if (query::$get['type'] == 'video') 
			if (query::$get['num']) $third = sets::video('full'); else $third = sets::video('thumb');
		switch (query::$get['type']) {
			case 'post': 
				$data['main']['post'] = $output->get_post(1,'id='.query::$get['id']); 
				$this->template = 'templates/main/post.php'; 
				break;
			case 'video': 
				$data['main']['video'] = $output->get_video(1,'id='.query::$get['id'],$third); 
				$this->template = 'templates/main/video.php'; 
				break;
			case 'order': 
				$data['main'] = $output->order_single(query::$get['id']); 
				$this->template = 'templates/main/order/single.php'; 
				break;
			case 'art': 
				$data['main']['art'] = $output->get_art(1,'id='.query::$get['id']); 
				$this->template = 'templates/main/booru/single.php'; 
				break;
			default: die;
		}

		$postparse = '/<div[^>]*class="innerwrap[^"]*"[^>]*>.*<\/div><!-- wrapend -->/uis';
		if (query::$get['num']) $data['main']['display']['comments'] = true; else $data['main']['display'] = array();
		return $data;
	}

	function save() {
		
		$input = 'input__'.query::$post['type']; $func = 'edit_'.query::$post['part'];
		$input = new $input;
		if (query::$post['type'] == 'order') query::$post['type'] = 'orders';
		$old_data = obj::db()->sql('select * from '.query::$post['type'].' where id='.query::$post['id'],1);
		$input->$func();
		$new_data = obj::db()->sql('select * from '.query::$post['type'].' where id='.query::$post['id'],1);
		if ($old_data != $new_data) {
			unset($new_data['id']);
			obj::db()->sql('update search set lastupdate=0 where place="'.query::$post['type'].'" and item_id="'.query::$post['id'].'"',0);
			if (query::$post['type'] == 'orders') query::$post['type'] = 'order';
			obj::db()->insert('versions',array(query::$post['type'],query::$post['id'],base64_encode(serialize($new_data)),ceil(microtime(true)*1000),sets::user('name'),$_SERVER['REMOTE_ADDR']));
		}
	}
	
	function remove_from_pool() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->num(query::$get['val'])) {
			obj::db()->sql('update art_pool set count = count - 1, art = replace(art,"|'.query::$get['val'].'|","|") where (id="'.query::$get['id'].'" and (password="" or password="'.md5(query::$get['password']).'"))',0);
			obj::db()->sql('update art set pool = replace(pool,"|'.query::$get['id'].'|","|") where id="'.query::$get['val'].'"',0);			
		}
	}	
	
	function sort_pool() {
		 global $check;
		if ($check->num(query::$get['id'])) {
			query::$post['art'] = '|'.implode('|',array_reverse(query::$post['art'])).'|';
			obj::db()->sql('update art_pool set art = "'.query::$post['art'].'" where (id="'.query::$get['id'].'" and (password="" or password="'.md5(query::$get['password']).'"))',0);
		}
	}	

	function title() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) 
			return array('value' => obj::db()->sql('select title from '.query::$get['type'].' where id='.query::$get['id'],2));
	}
	
	function text() {
		 global $check;
		$this->textarea = true;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) 
			return array('value' => obj::db()->sql('select pretty_text from '.query::$get['type'].' where id='.query::$get['id'],2));
	}

	function post_images() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'post') 
			return array('value' => array_filter(explode('|',obj::db()->sql('select image from post where id='.query::$get['id'],2))));
	}	
	
	function post_links() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'post') 
			return array('value' => unserialize(obj::db()->sql('select link from post where id='.query::$get['id'],2)));
	}	
	
	function post_bonus_links() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'post') {
			$value = unserialize(obj::db()->sql('select info from post where id='.query::$get['id'],2));
			$value ? null : $value = array(false);
			return array('value' => $value);
		}
	}	
	
	function post_files() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'post') 
			return array('value' => unserialize(obj::db()->sql('select file from post where id='.query::$get['id'],2)));
	}
	
	function video_link() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'video') 
			return array('value' => obj::db()->sql('select link from video where id='.query::$get['id'],2));
	}	
	
	function category() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) 
			return array('value' => array_filter(explode('|',obj::db()->sql('select category from '.query::$get['type'].' where id='.query::$get['id'],2))),
						'categories' => obj::db()->sql('select name, alias from category'.(query::$get['type'] != 'orders' ? ' where locate("|'.query::$get['type'].'|",area)' : '').' order by id','alias'));
	}	
	
	function language() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) 
			return array('value' => array_filter(explode('|',obj::db()->sql('select language from '.query::$get['type'].' where id='.query::$get['id'],2))),
						'languages' => obj::db()->sql('select name, alias from language order by id','alias'));			
	}
	
	function tag() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select tag from '.query::$get['type'].' where id='.query::$get['id'],2))));
			$meta = obj::db()->sql('select alias, name from tag where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) if ($meta[$one]) $one = $meta[$one];
		}
		return $return;
	}			
	
	function author() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select author from '.query::$get['type'].' where id='.query::$get['id'],2))));
			$meta = obj::db()->sql('select alias, name from author where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) if ($meta[$one]) $one = $meta[$one];
		}
		return $return;
	}

	function orders_username() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'orders') 
			return array('value' => obj::db()->sql('select username from orders where id='.query::$get['id'],2));
	}
	
	function orders_mail() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'orders') 
			return array('value' => obj::db()->sql('select email, spam from orders where id='.query::$get['id'],1));
	}	
	
	function art_source() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art') 
			return array('value' => obj::db()->sql('select source from art where id='.query::$get['id'],2));
	}		
	
	function art_image() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art') 
			return true;
	}		
	
	function art_groups() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art') {
			$return = obj::db()->sql('select id, name from art_pool','id');
			asort($return);
			return $return;			
		}
	}	
		
	function art_translations() {
		 global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art') 
			return true;
	}	
		
	function comment() {
		 global $check;
		if ($check->num(query::$get['id']) && $check->rights()) 
			return obj::db()->sql('select pretty_text,username from comment where id='.query::$get['id'],1);
	}			
}
