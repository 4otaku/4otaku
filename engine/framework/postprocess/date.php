<?
	
class Postprocess_Date implements Postprocess_Interface
{	
	public function process_web ($date) {		
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
		
		$time = (int) $time;
		
		$date = self::rumonth(date('m'), $time).date(' j, Y', $time);
		
		if ($minutes) {
			$date .= date('; G:i', $time);
		}
		
		return $date;
	}
}
