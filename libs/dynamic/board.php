<? 

class dynamic__board extends engine
{
	function delete(){
		
		
		$data = obj::db()->sql('select type, cookie from board where id='.query::$get['id'],1);
		
		if ($data['cookie'] != query::$cookie) {
			return false;
		}
		
		obj::db()->update('board','type',0,query::$get['id']);

		if ($data['type'] == 2) {
			obj::db()->update('board','type',0,query::$get['id'],'thread');
		}
	}
	
	function load_video(){
		
		$data = obj::db()->sql('select content from board where id='.query::$get['id'],2);
		$data = unserialize(base64_decode($data));
		
		$width = def::board('thumbwidth');
		$height = $width * $data['video']['aspect'];
		
		if (isset($data['video']['object'])) {
			return str_replace(
				array('%video_width%','%video_height%'),
				array($width,$height), 
				$data['video']['object']
			); 
		}
		
		return '<b>Ошибка!</b>';
	}
	
	function download(){
		
		$this->template = 'templates/download_error.php';
		
		query::$get['thread'] = (int) query::$get['thread'];
		
		switch (query::$get['type']) {
			case 'zip': 
				$return['main']['file'] = $this->get_zip(query::$get['thread']); 
				$return['main']['name'] = 'thread_'.query::$get['thread'].'.zip';
				break;
			default: return;
		}		
		
		if (!empty($return['main']['file'])) {
			$this->template = 'templates/download.php';
			return $return;
		}
	}
	
	function get_zip($id){
		$work_dir = ROOT_DIR.SL.'files'.SL.'board_cache'.SL;
		$image_dir = ROOT_DIR.SL.'images'.SL.'board'.SL.'full'.SL;
		$data = obj::db()->sql('select id, content, sortdate from board where `type`!="0" and (id='.$id.' or thread='.$id.')', 'sortdate');
		if (!empty($data)) {
			$images = array();
			foreach ($data as $key => $item) {
				$content = unserialize(base64_decode($item['content']));
				if (empty($content['image'])) {
					unset ($data[$key]);
				} else {
					$i = 0;
					foreach($content['image'] as $image) {
						$image_file_name = $item['id'] . ($i++ ? '_'.($i+1) : '') . '.' . end(explode('.', $image['full']));
						$images[$image_file_name] = $image['full'];
					}
				}			
			}
			$zip_name = 'thread_'.$id.'_'.max(array_keys($data)).'.zip';
			if (!file_exists($work_dir.$zip_name)) {
				$zip = new ZipArchive;
				if ($zip->open($work_dir.$zip_name, ZipArchive::CREATE) === true) {
					foreach ($images as $name => $image) {
						if (file_exists($image_dir.$image)) {
							$zip->addFile($image_dir.$image, $name);
						}
					}
					$zip->close();
				}
			}
			return $work_dir.$zip_name;
		}
		
		return false;
	}
}