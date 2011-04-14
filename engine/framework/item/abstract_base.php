<?

abstract class Item_Abstract_Base extends Array_Access implements Plugins
{
	protected $flag;
	
	public function __construct ($data, $flag = false) {
		
		$this->data = (array) $data;
		$this->flag = $flag;	
	}
}
