<?

abstract class Input extends Core implements Plugins
{
	public $redirect_address = false;
	public $status_message = false;
	
	public function process ($query) {
		$function = $query['function'];
		
		return $this->$function($query);
	}
}
