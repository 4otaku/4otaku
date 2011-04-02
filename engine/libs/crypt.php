<?
	
class Crypt
{	
	const PACKED_MARK = '=packed=';
	
	public static function pack($data) {
		if (function_exists('igbinary_serialize')) {
			$data = igbinary_serialize($data);
		} else {
			$data = serialize($data);
		}
		
		return self::PACKED_MARK.rtrim(base64_encode($data),'=');
	}
	
	public static function unpack($input_string) {
		$mark_length = strlen(self::PACKED_MARK);
		
		if (substr($input_string, 0, $mark_length) != self::PACKED_MARK) {
			return $input_string;
		}
		
		$string = substr($input_string, $mark_length);		
		$string = base64_decode($string);
		
		if (empty($string)) {
			return $input_string;
		}
		
		if (function_exists('igbinary_unserialize')) {
			return igbinary_unserialize($string);
		} else {
			return unserialize($string);
		}
	}
}
