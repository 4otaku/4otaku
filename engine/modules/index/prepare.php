<?

class Index_Prepare extends Prepare implements Plugins
{
	public $url_parts = array();
	
	public function postprocess ($data) {
		$worker = new Postprocess_Index();
		
		return $worker->process_web($data);
	}
}
