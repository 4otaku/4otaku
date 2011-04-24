<?

// Блоки содержащие мета-данные: теги, категории и.т.д.

abstract class Item_Abstract_Meta extends Item_Abstract_Marked implements Plugins
{	
	public function postprocess () {

		if (!empty($this->data['meta']) && is_array($this->data['meta'])) {
			
			$singluar = Config::template('singular');
			$plural = Config::template('plural');			
			
			$this->data['base'] = '/'.$this->data['item_type'].'/';
			$this->data['base'] .= $this->data['area'] == 'main'? '' : $this->data['area'].'/';

			$this->data['meta_header'] = array();

			foreach ($this->data['meta'] as $type => $items) {
				
				if (count($items) < 2 && array_key_exists($type, $singluar)) {
					$this->data['meta_header'][$type] = $singluar[$type];
					
				} elseif (array_key_exists($type, $plural)) {
					$this->data['meta_header'][$type] = $plural[$type];
				}

				if ($type == 'tag') {
					foreach ($items as $tag) {
						if (!empty($tag['variants'])) {
							$this->data['have_tag_variants'] = true;
							break;
						}
					}
				}
			}
		}

		if (!empty($this->data['date'])) {
						
			$this->data['date'] = Database::date_to_unix($this->data['date']);
			
			$this->data['precise_date'] = Transform_String::rudate($this->data['date'], true);
			$this->data['date'] = Transform_String::rudate($this->data['date']);
		}
	}
}
