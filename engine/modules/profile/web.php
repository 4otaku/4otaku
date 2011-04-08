<?

class Profile_Web extends Module_Web implements Plugins
{
	public $url_parts = array('part');
	
	public function postprocess ($data) {
		
		return $data;	
	}	
}
