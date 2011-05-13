<?

// Блок содержащий навигацию по страницам

class Item_Navi extends Item_Abstract_Marked implements Plugins
{	
	public function postprocess () {

		$query = $this->data['query'];
		$module = $this->data['module'];		
		unset ($this->data['query'], $this->data['module']);

		$this->data['pages'] = array();		
		
		$this->data['base'] = $this->get_base($module, $query);

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
					$this->data['pages'][$i]['url'] = $this->data['base'];
				} else {
					$this->data['pages'][$i]['url'] = $this->data['base'].'page/'.$i.'/';
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
			$this->data['backward'] = $this->data['base'];
		} elseif ($this->data['curr_page'] > 2) {
			$this->data['backward'] = $this->data['base'].'page/'.($this->data['curr_page'] - 1).'/';
		}

		if ($this->data['curr_page'] < $this->data['pagecount']) {
			$this->data['forward'] = $this->data['base'].'page/'.($this->data['curr_page'] + 1).'/';
		}
	}
	
	protected function get_base ($module, $query) {
		$base = '/'.$module.'/';
		
		$base .= !empty($this->data['submodule']) ? $this->data['submodule'].'/' : '';
		$base .= !empty($query['section']) ? $query['section'].'/' : '';

		if (!empty($query['area']) && $query['area'] != 'main') {
			$base .= $query['area'].'/';
		}

		if (!empty($query['meta']) && !empty($query['alias'])) {
			$base .= empty($this->flag['short_base']) ? $query['meta'].'/' : '';
			$base .= $query['alias'].'/';
		}
		
		return $base;		
	}
}
