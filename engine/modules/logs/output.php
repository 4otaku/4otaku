<?

class Logs_Output extends Output implements Plugins
{	
	private static $worker;
	
	public function main ($query) {
		$query['section'] = 'main';
		
		$this->section($query);
	}
	
	public function section ($query) {
		
		$type = Config::settings('sections', $query['section'], 'type');		
		$worker = 'Logs_Submodule_'.ucfirst($type);

		self::$worker = new $worker($query['section']);

		$this->items = self::$worker->get_data($query);		
	}
	
	public static function description ($query) {		
		
		$return = array();
		
		$start = self::$worker->get_start();
		
		if (empty($start)) {
			Error::fatal("Для этой комнаты нет логов");
		}
		
		$start = explode('-', $start);
		$end = array(date('Y'), date('n'), date('j'));		
		$current = array($query['year'], $query['month'], $query['day']);
		
		for ($i = $start[0]; $i <= $end[0]; $i++) {
			$start_month = $i == $start[0] ? (int) $start[1] : 1;
			$end_month = $i == $end[0] ? (int) $end[1] : 12;
			
			for ($j = $start_month; $j <= $end_month; $j++) {
				$return['month'][$i*100 + $j] = array(
					'type' => ($i == $current[0] && $j == $current[1] ? 'current' : 'active'),
					'year' => $i, 
					'month' => $j,
					'name' => Transform_String::rumonth($j).' '.$i,
					'section' => isset($query['section']) ? $query['section'] : 'main',
				);
			}
		}
		
		ksort($return['month']);
		
		$yesterday = mktime(12,0,0,$current[1],$current[2]-1,$current[0]); 
		$tomorrow = mktime(12,0,0,$current[1],$current[2]+1,$current[0]);
		
		if ($yesterday > mktime(0,0,0,$start[1],$start[2],$start[0])) {
			$year = date("Y", $yesterday);
			$month = date("n", $yesterday);
			$day = date("j", $yesterday);
			
			$return['navi']['yesterday']= array(
				'year' => $year,
				'month' => $month,
				'day' => $day,
				'name' => Transform_String::rumonth($month).' '.$day,
				'section' => isset($query['section']) ? $query['section'] : 'main',
			);
			
		} elseif ($yesterday < mktime(0,0,0,$start[1],$start[2]-1,$start[0])) {
			$return['nologs']['past'] = true;
		}
		
		$return['arrow']['today']= array('name' => Transform_String::rumonth($current[1]).' '.$current[2]);
		
		if ($tomorrow < mktime(24,0,0,$end[1],$end[2],$end[0])) {
			$year = date("Y", $tomorrow);
			$month = date("n", $tomorrow);
			$day = date("j", $tomorrow);
			
			$return['navi']['tomorrow']= array(
				'year' => $year,
				'month' => $month,
				'day' => $day,
				'name' => Transform_String::rumonth($month).' '.$day,
				'section' => isset($query['section']) ? $query['section'] : 'main',
			);
			
		} elseif ($tomorrow > mktime(24,0,0,$end[1],$end[2]+1,$end[0])) {
			$return['nologs']['future'] = true;
		}
		
		return $return;				
	}	
}
