<?

class Login_Output extends Module_Output implements Plugins
{
	public function main () {
		
		return Globals::user_info();
	}
}
