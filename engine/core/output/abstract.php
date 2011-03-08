<?

abstract class Output_Abstract
{
	protected $class_name = '';
	
	public function __construct($class_name) {
		$this->call = Plugins::extend($this);
		
		$this->class_name = $class_name;
	}
	
	public function common_postprocess($data) {		
		$data['domain'] = 'http://beta.4otaku.ru';
		
		if (!empty($data['items']) && is_array($data['items'])) {
			foreach ($data['items'] as & $item) {
				$item['item_type'] = $this->class_name;
			}
		}
		
		return $data;
	}
	
	public function build_listing_condition($query) {	
		$condition = "area = 'main'";
		
		if (!empty($query['meta']) && !empty($query['alias'])) {
			$condition .= " and match (meta) against ('+{$query['meta']}_{$query['alias']}' in boolean mode)";
		}
		
		return $condition;
	}
}
