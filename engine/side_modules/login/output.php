<?

class Login_Output extends Output implements Plugins
{
	public function main () {
		
		return Globals::user_info();
	}
}
