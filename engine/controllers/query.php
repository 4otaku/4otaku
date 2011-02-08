<?
// Начало работы контроллера, определение типа запроса.
	
class Query
{
	function __construct() {
		$this->call = Plugins::extend();
	}
	
	function get_controller() {		
		if (!empty(Globals::$user['mobile'])) {
			
		}
	}
}
