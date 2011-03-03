<?

class Transform_Web_Output
{
	public function check_index(& $url) {		
		if ($url == array('index')) {
			unset($url);
			
			return array('function' => 'index');
		}
	}	
	
	public function get_download(& $url) {
		
		if (isset($url[0]) && $url[0] == 'download') {
			array_shift($url);
			
			return array('download' => true);
		}
	}
	
	public function get_mixed($url) {
		
		if (isset($url[1]) && $url[1] == 'mixed') {
			$mixed = Mixed::parse($url[2]);
			
			array_splice($url, 1, 2);
			
			return array('mixed' => $mixed, 'function' => 'listing');
		}
	}
	
	public function get_id($url) {		
		
		if (isset($url[1]) && is_numeric($url[1])) {
			
			$id = array_splice($url, 1, 1);
			
			return array_merge($url, array('id' => current($id), 'function' => 'single'));
		}
		
		return $url;
	}
	
	
}
