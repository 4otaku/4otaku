<?

class Index_Web extends Module_Web implements Plugins
{
	public $url_parts = array();
	
	public function postprocess ($data) {
		$worker = new Process_Index();
		
		return $worker->process_web($data);
	}
}
