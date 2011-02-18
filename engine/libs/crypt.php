<?
	
class Crypt
{	
	public function pack_array($array) {
		if (function_exists('igbinary_serialize')) {
			$array = igbinary_serialize($array);
		} else {
			$array = serialize($array);
		}
		
		return rtrim(base64_encode($array),'=');
	}
	
	public function unpack_array($string) {
		$string = base64_decode($string);
		
		if (function_exists('igbinary_unserialize')) {
			return igbinary_unserialize($string);
		} else {
			return unserialize($string);
		}
	}
}
