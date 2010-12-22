<? 

class dinamic__board extends engine
{
	function delete(){
		global $get;
		
		$data = obj::db()->sql('select type, cookie from board where id='.$get['id'],1);
		
		if ($data['cookie'] != $_COOKIE['settings']) {
			return false;
		}
		
		obj::db()->update('board','type',0,$get['id']);

		if ($data['type'] == 2) {
			obj::db()->update('board','type',0,$get['id'],'thread');
		}
	}
	
	function download(){
		global $get;
		
		$get['thread'] = (int) $get['thread'];
		
		switch ($get['type']) {
			case 'zip': 
				$return['main']['file'] = $this->get_zip($get['thread']); 
				$return['main']['name'] = 'thread_'.$get['thread'].'.zip';
				break;
			default: return;
		}		
		
		$this->template = 'templates/download.php';
		return $return;
	}
	
	function get_zip($id){
		$work_dir = ROOT_DIR.SL.'files'.SL.'board_cache'.SL;
		$image_dir = ROOT_DIR.SL.'images'.SL.'board'.SL.'full'.SL;
		$data = obj::db()->sql('select id, content, sortdate from board where id='.$id.' or thread='.$id, 'sortdate');
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
}