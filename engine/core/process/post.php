<?

class Process_Post
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function listing($query) {
		$return = array();
		
		$start = $query['start'] - 1;
		$number = $query['end'] - $query['start'] + 1;
		
		$condition = "area = 'main' order by date desc limit $start, $number";
		
		$return['posts'] = Globals::db()->get_table('post',$condition);

		return $return;
	}
}
