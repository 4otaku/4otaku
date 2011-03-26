<?

abstract class Module_Web extends Module_Web_Library implements Plugins
{
	public function make_query ($url) {
		$query = array();
		
		if (is_array($this->url_parts)) {
			foreach ($this->url_parts as $part) {
				$function = 'get_'.$part;
				if (is_callable(array($this, $function))) {
					$query = array_merge($query, (array) $this->$function($url));
				}
			}
		}
		
		return $query;
	}
	
	abstract public function postprocess ($data);
	
	protected function postprocess_navi ($data) {
		if (!empty($data['pagecount']) && !empty($data['curr_page'])) {
			$worker = new Postprocess_Navi();
			return $worker->process_web($data);
		}
		
		return $data;
	}
	
	protected function postprocess_items ($data) {
		if (!empty($data['items'])) {
			$meta_worker = new Postprocess_Meta();
			$date_worker = new Postprocess_Date();

			foreach ($data['items'] as & $item) {
				if (!empty($item['meta'])) {
					$item = $meta_worker->process_web($item);
				}

				if (!empty($item['date'])) {
					$item['date'] = $date_worker->process_web($item['date']);
				}
			}
			unset ($item);
		}
		
		return $data;
	}
}
