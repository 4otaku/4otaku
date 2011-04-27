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
			
			if (!empty($start)) {
				Config::update('settings', 'sections', $this->name, 'start', $start);
			}
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
	
			if ($this->completed_day($query)) {
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
		}
		
		foreach ($data as & $row) {
			$row['item_type'] = 'log';
		}
		
		return $data;
	}
	
	abstract protected function extract_start();
	
	abstract protected function extract_data($query);
		
	protected function completed_day($query) {
		
		$day = $query['year'].'-'.$query['month'].'-'.$query['day'];
		$day = new DateTime($day);
		$day->add(new DateInterval('P1D'));
		
		$today = new DateTime();
		
		return (bool) ($today > $day);
	}
		
	protected function make_safe($text) {
		
		$text = str_replace(array('<','>'), array('&lt;','&gt;'), $text);
		$text = preg_replace(
			array('/http:\/\/([^\/]+)[^\s]*/s', '/[\r\n]+/su'), 
			array('<a href="$0">$0</a>', '<br />'), 
			$text
		);
		
		return Transform_Text::cut_long_words($text, 40);
	}
}
