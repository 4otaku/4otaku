<?

class Sidebar_Output extends Output implements Plugins
{
	public function main ($query) {	
		Config::load(__DIR__.SL.'settings.ini', true);
		
		var_dump(Globals::user('sidebar'));
	}
}
