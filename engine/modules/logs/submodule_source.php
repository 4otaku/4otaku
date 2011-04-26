<?

abstract class Logs_Submodule_Source implements Plugins
{
	protected $config = array();
	protected $name;
	
	public function __construct($name) {

		$this->config = Config::settings('sections', $name);
		$this->name = $name;
	}
	
	public function get_start() {
		$start = $this->config['start'];
		
		if (empty($start)) {
			$start = $this->extract_start();
			
			Config::update('settings', 'sections', $this->name, 'start', $start);
		}
		
		return $start;
	}
	
	public function get_data($query) {
		
		$condition = "`section` = ? and `year` = ? and `month` = ? and `day` = ?";
		$params = array($this->name, $query['year'], $query['month'], $query['day']);
		
		$data = Database::get_field('logs', 'data', $condition, $params);
		$data = Crypt::unpack($data);
	
		if (empty($data)) {
			$data = $this->extract_data($query);
	
			$insert = array(
				'data' => Crypt::pack($data),
				'section' => $this->name, 
				'year' => $query['year'],
				'month' => $query['month'],
				'day' => $query['day'],
			);
			
			$dont_update = array('section', 'year', 'month', 'day');
			
			Database::replace('logs', $insert, $dont_update);
		}
		
		return $data;
	}
	
	abstract protected function extract_start();
	
	abstract protected function extract_data($query);
}
