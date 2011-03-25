<?

class Video_Web extends Module_Web implements Plugins
{
	public $url_parts = array('area', 'mixed', 'meta', 'page', 'id');
	
	public function postprocess ($data) {
		
		$data = $this->postprocess_items($data);
		$data = $this->postprocess_navi($data);		
		
		return $data;
	}
}
