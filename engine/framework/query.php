<?

class Query extends Query_Library implements Plugins
{

/*	
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
	} */
}
