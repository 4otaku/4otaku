<?
	
class Postprocess_Date implements Postprocess_Interface
{	
	public function process_web ($date) {		
		return self::rudate($date);
	}
	

}
