<?

class Item_News extends Item_Abstract_Marked implements Plugins
{
	public function postprocess () {

		if (!empty($this->data['date'])) {
						
			$this->data['date'] = Database::date_to_unix($this->data['date']);
			
			$this->data['precise_date'] = Transform_String::rudate($this->data['date'], true);
			$this->data['date'] = Transform_String::rudate($this->data['date']);
		}
	}
}
