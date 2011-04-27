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
		
		$start = new DateTime($start);
		$end = new DateTime();		
		$current = new DateTime($query['year'].'-'.$query['month'].'-'.$query['day']);
		
		$i = clone $start;
		
		while ($i < $end) {
			$return['month'][$i->format('U')] = array(
				'type' => ($i->format('Ym') == $current->format('Ym') ? 'current' : 'active'),
				'year' => $i->format('Y'), 
				'month' => $i->format('n'),
				'name' => Transform_String::rumonth($i->format('n')).' '.$i->format('Y'),
				'section' => isset($query['section']) ? $query['section'] : 'main',
			);
			
			$i->add(new DateInterval('P1M'));
		}
		
		ksort($return['month']);
		
		$yesterday = clone $current; 
		$yesterday->sub(new DateInterval('P1D'));
		$tomorrow = clone $current;
		$tomorrow->add(new DateInterval('P1D'));
		
		if ($yesterday > $start) {
						
			$return['navi']['yesterday']= array(
				'year' => $yesterday->format('Y'),
				'month' => $yesterday->format('n'),
				'day' => $yesterday->format('j'),
				'name' => Transform_String::rumonth($yesterday->format('n')).' '.$yesterday->format('j'),
				'section' => isset($query['section']) ? $query['section'] : 'main',
			);
			
		} elseif ($current < $start) {
			$return['nologs']['past'] = true;
		}
		
		$return['navi']['today']= array(
			'name' => Transform_String::rumonth($current->format('n')).' '.$current->format('j')
		);
		
		if ($tomorrow < $end) {
			
			$return['navi']['tomorrow']= array(
				'year' => $tomorrow->format('Y'),
				'month' => $tomorrow->format('n'),
				'day' => $tomorrow->format('j'),
				'name' => Transform_String::rumonth($tomorrow->format('n')).' '.$tomorrow->format('j'),
				'section' => isset($query['section']) ? $query['section'] : 'main',
			);
			
		} elseif ($current > $end) {
			$return['nologs']['future'] = true;
		}

		return $return;				
	}	
}
