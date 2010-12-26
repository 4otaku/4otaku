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
	
	function load_video(){
		global $get;
		$data = obj::db()->sql('select content from board where id='.$get['id'],2);
		$data = unserialize(base64_decode($data));
		
		$width = def::board('thumbwidth');
		$height = $width * $content['video']['aspect'];
		
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
		global $get;
		$this->template = 'templates/download_error.php';
		
		$get['thread'] = (int) $get['thread'];
		
		switch ($get['type']) {
			case 'zip': 
				$return['main']['file'] = $this->get_zip($get['thread']); 
				$return['main']['name'] = 'thread_'.$get['thread'].'.zip';
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