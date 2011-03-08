<?
	
class Crypt
{	
	public static function pack($data) {
		if (function_exists('igbinary_serialize')) {
			$data = igbinary_serialize($data);
		} else {
			$data = serialize($data);
		}
		
		return rtrim(base64_encode($data),'=');
	}
	
	public static function unpack($string) {
		$string = base64_decode($string);
		
		if (function_exists('igbinary_unserialize')) {
			return igbinary_unserialize($string);
		} else {
			return unserialize($string);
		}
	}
}
