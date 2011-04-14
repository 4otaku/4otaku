<?

// Блок содержащий навигацию по страницам

class Item_Navi extends Item_Abstract_Marked implements Plugins
{	
	public function postprocess () {

		$query = $this->data['query'];
		$module = $this->data['module'];		
		unset ($this->data['query'], $this->data['module']);

		$this->data['pages'] = array();		
		
		$base = '/'.$module.'/';
		
		$base .= !empty($this->data['navi_base']) ? $this->data['navi_base'].'/' : '';

		if (!empty($query['area']) && $query['area'] != 'main') {
			$base .= $query['area'].'/';
		}

		if (!empty($query['meta']) && !empty($query['alias'])) {
			$base .= $query['meta'].'/'.$query['alias'].'/';
		}

		$radius = max(1, (int) Config::template('navi_radius'));
		$low_end = max($this->data['curr_page'] - $radius, 2);
		$high_end = min($this->data['curr_page'] + $radius, $this->data['pagecount'] - 1);

		for ($i = 1; $i <= $this->data['pagecount']; $i++) {

			$inside = ($i <= $high_end && $i >= $low_end);

			if ($i == 1 || $inside || $i == $this->data['pagecount']) {
				if ($i == $this->data['curr_page']) {
					$this->data['pages'][$i] = array('type' => 'active');
				} else {
					$this->data['pages'][$i] = array('type' => 'enabled');
				}

				if ($i == 1) {
					$this->data['pages'][$i]['url'] = $base;
				} else {
					$this->data['pages'][$i]['url'] = $base.'page/'.$i.'/';
				}
			} else {
				$this->data['pages'][$i] = array('type' => 'skip');
				if ($i > $high_end) {
					$i = $this->data['pagecount'] - 1;
				} else {
					$i = $low_end - 1;
				}
			}
		}

		if ($this->data['curr_page'] == 2) {
			$this->data['backward'] = $base;
		} elseif ($this->data['curr_page'] > 2) {
			$this->data['backward'] = $base.'page/'.($this->data['curr_page'] - 1).'/';
		}

		if ($this->data['curr_page'] < $this->data['pagecount']) {
			$this->data['forward'] = $base.'page/'.($this->data['curr_page'] + 1).'/';
		}
	}
}
