<?

class Item_Video extends Item_Abstract_Meta implements Plugins
{
	public function postprocess () {
		parent::postprocess();
		
		$this->data['object'] = Crypt::unpack($this->data['object']);
		
		if (!empty($this->flag)) {
			$this->data['object'] = str_replace(
				array('%video_width%','%video_height%'),
				Config::settings((string) $this->flag),
				$this->data['object']
			);
		}
	}
}
