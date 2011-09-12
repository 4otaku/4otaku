<? 

class dynamic__board extends engine
{
	private static $work_dir;
	
	function delete() {		
		$data = obj::db()->sql('select type, cookie from board where id='.query::$get['id'],1);
		
		if ($data['cookie'] != query::$cookie) {
			return false;
		}
		
		obj::db()->update('board','type','deleted',query::$get['id']);

		if ($data['type'] == 'thread') {
			obj::db()->update('board','type','deleted',query::$get['id'],'thread');
		}
	}
	
	function load_video() {
		
		$id = explode('-', query::$get['id']);
		$post_id = (int) $id[0];
		$order = (int) $id[1];
		
		$data = obj::db()->sql('
			select data 
			from board_attachment 
			where 
				post_id='.$post_id.' and 
				type="video"
			order by `order`
			limit '.$order.', 1'
		,2);
		
		$data = unserialize(base64_decode($data));
		
		$width = def::board('thumbwidth');
		$height = $width * $data['aspect'];
		
		if (!empty($data['object'])) {
			return str_replace(
				array('%video_width%','%video_height%'),
				array($width,$height), 
				$data['object']
			); 
		}
		
		return '<b>Ошибка!</b>';
	}
	
	function download() {		
		$this->template = 'templates/download_error.php';
		
		query::$get['thread'] = (int) query::$get['thread'];
		self::$work_dir = ROOT_DIR.SL.'files'.SL.'board_cache'.SL;
		
		switch (query::$get['type']) {
			case 'zip': 
				$return['main']['file'] = $this->get_zip(query::$get['thread']); 
				$return['main']['name'] = 'thread_'.query::$get['thread'].'.zip';
				break;
			case 'html': 
				$return['main']['file'] = $this->get_html(query::$get['thread']); 
				$return['main']['name'] = 'thread_'.query::$get['thread'].'.html';
				break;				
			default: return;
		}		
		
		if (!empty($return['main']['file'])) {
			$this->template = 'templates/download.php';
			return $return;
		}
	}
	
	function get_zip($id) {
		$image_dir = ROOT_DIR.SL.'images'.SL.'board'.SL.'full'.SL;
		$data = obj::db()->sql('select id, sortdate from board where `type`!="deleted" and (id='.$id.' or thread='.$id.')', 'id');
		$ids = array();
		foreach ($data as $key => $item) {
			$ids[] = $key;
			$data[$key] = array('id' => $key, 'sortdate' => $item);
		}
	
		if (!empty($data)) {
			$attachments = obj::db()->sql('
				SELECT post_id, `data` FROM board_attachment 
				WHERE type="image" and post_id in ('.implode(',',$ids).') 
				ORDER by `order`');

			foreach ($attachments as $file) {
				$data[$file['post_id']]['image'][] = unserialize(base64_decode($file['data']));
			}

			$images = array();
			foreach ($data as $key => $item) {

				if (empty($item['image'])) {
					unset ($data[$key]);
				} else {
					$i = 0;
					foreach($item['image'] as $image) {

						$image_file_name = $item['id'] . ($i++ ? '_'.($i+1) : '') . '.' . end(explode('.', $image['full']));
						$images[$image_file_name] = $image['full'];
					}
				}			
			}
			$zip_name = 'thread_'.$id.'_'.max(array_keys($data)).'.zip';
			if (!file_exists(self::$work_dir.$zip_name)) {
				$zip = new ZipArchive;
				if ($zip->open(self::$work_dir.$zip_name, ZipArchive::CREATE) === true) {
					foreach ($images as $name => $image) {
						if (file_exists($image_dir.$image)) {
							$zip->addFile($image_dir.$image, $name);
						}
					}
					$zip->close();
				}
			}
			return self::$work_dir.$zip_name;
		}
		
		return false;
	}
	
	function get_html($id) {
		$updated = obj::db()->sql('select updated from board where `type`!="deleted" and id='.$id,2);
		if (!empty($updated)) {
			$file_name = 'thread_'.$id.'_'.$updated.'.html';
			
			if (!file_exists(self::$work_dir.$file_name)) {
				
				$css = file_get_contents(ROOT_DIR.SL.'jss'.SL.'main.css');
				preg_match('/^\/\*.+?\*\/(.*?)\/\*.*\/\*\s+Content,\s+борда\s+\*\/(.*?)\/\*/uis',$css,$css);
				$style = '<style>'.$css[1].$css[2].'</style>';
				$encoding = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				$head = '<head>'.$encoding.$style.'</head>';
				$body = '<body>'.$this->curl('http://'.def::site('domain').'/board/download/thread/'.$id).'</body>';
				
				$body = preg_replace_callback('/<img[^>]+>/si',array('self','image_encode'),$body);
				
				$file = fopen(self::$work_dir.$file_name,'w');
				fwrite($file,preg_replace('/[\n\r\t]+/','','<html>'.$head.$body.'</html>'));
				fclose($file);
			}
			
			return self::$work_dir.$file_name;
		}
		
		return false;
	}
	
	function curl($link) {
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		
		curl_setopt($ch, CURLOPT_URL, $link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$input = curl_exec($ch);
		curl_close($ch);
		
		return $input;
	}
	
	static function image_encode($img) {
		$img = $img[0];
		preg_match('/src="([^"]+)"/is',$img,$src);
		$src = $src[1];
		
		$data = fread(fopen(ROOT_DIR.SL.$src,'r'),filesize(ROOT_DIR.SL.$src));
		$data = chunk_split(base64_encode($data));
	
		$ext = end(explode('.',$src));
		
		switch ($ext) {
			case 'jpg':
			case 'jpeg':
				return str_replace($src,'data:image/jpeg;base64,'.$data,$img);
			case 'gif':
				return str_replace($src,'data:image/gif;base64,'.$data,$img);
			case 'png':
				return str_replace($src,'data:image/png;base64,'.$data,$img);
			default:
				return str_replace($src,'http://'.def::site('domain').$src,$img);
		}
	}
}
