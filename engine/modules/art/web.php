<?

class Art_Web extends Module_Web implements Plugins
{	
	public $url_parts = array('area', 'mixed', 'meta', 'pool', 'pack', 'page', 'id');
	
	protected $pack_text_replacements = array(
		'/\[url=(.+?)\](.+?)\[\/url\]/' => '$2: $1',
		'/\[.+?\](.+?)\[\/.+?\]/' => '$1',
		'/\n/' => '<br />',
	);
	
	public function postprocess ($data) {
		
		$data = $this->postprocess_items($data);
		$data = $this->postprocess_navi($data);
		
		if (Globals::$query['function'] == 'pack_list') {
			foreach ($data['items'] as & $pack) {
				$pack['text'] = preg_replace(
					array_keys($this->pack_text_replacements), 
					array_values($this->pack_text_replacements),
					trim(htmlspecialchars($pack['pretty_text']))
				);
			}
		}
		
		return $data;	
	}
}
