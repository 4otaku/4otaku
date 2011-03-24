<?

class Error_Output extends Module_Output implements Plugins
{
	public function main () {
		$return = array();

		$return['pic'] = Globals::db()->get_full_row('art', "area != 'deleted' order by RAND()");

		$return['template'] = 'error';

		return $return;
	}
	
	public function class_not_found () {
		return $this->main();
	}
}
