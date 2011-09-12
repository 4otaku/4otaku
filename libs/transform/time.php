<?

class Transform_Time
{
	protected static $rumonth = array(
		'','Январь','Февраль','Март','Апрель',
		'Май','Июнь','Июль','Август',
		'Сентябрь','Октябрь','Ноябрь','Декабрь');

	public static function parse ($string, $add_current = true) {
		$parts = preg_split('/([^\d]+)/', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$time = 0;

		for ($i = 1; $i < count($parts); $i += 2) {
			$parts[$i] = trim($parts[$i]);
			switch ($parts[$i]) {
				case 'm': $multiplier = MINUTE; break;
				case 'h': $multiplier = HOUR; break;
				case 'd': $multiplier = DAY; break;
				case 'w': $multiplier = WEEK; break;
				case 'M': $multiplier = MONTH; break;
				default: $multiplier = 0; break;
			}

			$time = $time + $parts[$i - 1] * $multiplier;
		}

		return $time + (int) $add_current * time();
	}

	public static function ru_month($in) {

		if (is_numeric($in)) {
			return self::$rumonth[ltrim($in,'0')];
		}

		return array_search($in, self::$rumonth);
	}

	public static function ru_date($minutes = false) {
		$date = self::ru_month(date('m')).date(' j, Y');
		if ($minutes) $date .= date('; G:i');
		return $date;
	}

	public static function rudate($minutes = false) {
		return self::ru_date($minutes);
	}
}
