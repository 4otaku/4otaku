<?
	
class Process_Navi extends Process_Abstract
{	
	public function web($data) {
		
		$data['navi'] = array();
		
		$base = '/post/';
		
		$radius = max(1, (int) Config::template('navi', 'Radius'));
		$low_end = max($data['curr_page'] - $radius, 2);
		$high_end = min($data['curr_page'] + $radius, $data['pagecount'] - 1);
		
		for ($i = 1; $i <= $data['pagecount']; $i++) {
			
			$inside = ($i <= $high_end && $i >= $low_end);
			
			if ($i == 1 || $inside || $i == $data['pagecount']) {
				if ($i == $data['curr_page']) {
					$data['navi'][$i] = array('type' => 'active');
				} else {
					$data['navi'][$i] = array('type' => 'enabled');
				}
				
				if ($i == 1) {
					$data['navi'][$i]['url'] = $base;
				} else {
					$data['navi'][$i]['url'] = $base.'page/'.$i.'/';
				}
			} else {
				$data['navi'][$i] = array('type' => 'skip');
				if ($i > $high_end) {
					$i = $data['pagecount'] - 1;
				} else {
					$i = $low_end - 1;
				}
			}
		}
		
		if ($data['curr_page'] == 2) {
			$data['navi_back'] = $base;
		} elseif ($data['curr_page'] > 2) {
			$data['navi_back'] = $base.'page/'.($data['curr_page'] - 1).'/';
		}
		
		if ($data['curr_page'] < $data['pagecount']) {
			$data['navi_forward'] = $base.'page/'.($data['curr_page'] + 1).'/';
		}
		
		return $data;
	}
}
