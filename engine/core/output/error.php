<?

class Output_Error extends Output_Abstract
{
	public function class_not_found() {
		$return = array();

		$return['pic'] = Globals::db()->get_full_row('art', "area != 'deleted' order by RAND()");

		$return['template'] = 'error';

		return $return;
	}
}
