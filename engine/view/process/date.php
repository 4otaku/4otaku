<?
	
class Process_Date extends Process_Abstract
{	
	public function web($date) {		
		return $this->call->rudate($date);
	}
	
	public function rumonth($in) {
		$rumonth = array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
		
		if (is_numeric($in)) {
			return $rumonth[ltrim($in,'0')];
		}
		
		return array_search($in,$rumonth);
	}

	public function rudate($time = false, $minutes = false) {
		if (empty($time)) {
			$time = time();
		}
		
		$date = $this->call->rumonth(date('m'), $time).date(' j, Y', $time);
		
		if ($minutes) {
			$date .= date('; G:i', $time);
		}
		
		return $date;
	}
}
