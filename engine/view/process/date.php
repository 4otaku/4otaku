<?
	
class Process_Date
{	
	public static function web ($date) {		
		return self::rudate($date);
	}
	
	public static function rumonth($in) {
		$rumonth = array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
		
		if (is_numeric($in)) {
			return $rumonth[ltrim($in,'0')];
		}
		
		return array_search($in,$rumonth);
	}

	public static function rudate($time = false, $minutes = false) {
		if (empty($time)) {
			$time = time();
		}
		
		$date = self::rumonth(date('m'), $time).date(' j, Y', $time);
		
		if ($minutes) {
			$date .= date('; G:i', $time);
		}
		
		return $date;
	}
}
