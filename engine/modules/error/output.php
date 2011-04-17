<?

class Error_Output extends Output implements Plugins
{
	public function main () {

		$this->items['pic'] = Database::get_full_row('art', "area != 'deleted' order by RAND()");

	}
	
	public function class_not_found () {
		return $this->main();
	}
}
