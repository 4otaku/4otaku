<?

class Process_Meta extends Process_Abstract
{
	protected $singluar = array();
	protected $plural = array();

	public function web($item) {
		if (empty($this->singluar)) {
			$this->singluar = Config::template('singular');
		}

		if (empty($this->plural)) {
			$this->plural = Config::template('plural');
		}

		$item['base'] = '/'.$item['item_type'].'/';
		$item['base'] .= $item['area'] == 'main'? '' : $item['area'].'/';

		$item['meta_header'] = array();

		foreach ($item['meta'] as $type => $items) {
			if (count($items) < 2 && array_key_exists($type, $this->singluar)) {
				$item['meta_header'][$type] = $this->singluar[$type];
			} elseif (array_key_exists($type, $this->plural)) {
				$item['meta_header'][$type] = $this->plural[$type];
			}

			if ($type == 'tag') {
				foreach ($items as $tag) {
					if (!empty($tag['variants'])) {
						$item['have_tag_variants'] = true;
						break;
					}
				}
			}
		}


		return $item;
	}
}
