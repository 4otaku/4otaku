<?

class Archive_Web extends Module_Web implements Plugins
{
	public $url_parts = array('section');
	
	public function postprocess ($data) {
		$data = $this->postprocess_archive($data);
		
		return $data;	
	}	
	
	protected function postprocess_archive ($data) {
		$data['total'] = 0;
		
		$types = array('post' => 'записей', 'video' => 'видео');
		
		if (!empty($data['items'])) {

			foreach ($data['items'] as & $item) {
				
				foreach ($item['years'] as $year => & $months) {
					foreach ($months as $number => & $month) {
						$count = count($month);
						$data['total'] = $data['total'] + $count;

						$month = array(
							'items' => $month,
							'name' => Postprocess_Date::rumonth($number),
							'count' => $count
						);
						
						
					}
				}
			}
			unset ($item);
		}
		
		$data['list_type'] = Globals::$query['section'];
		
		$data['total'] .= ' '.$types[Globals::$query['section']];
				
		return $data;
	}
}
