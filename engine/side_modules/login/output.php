<?

class Login_Output extends Output implements Plugins
{
	public function main () {
		
		return Globals::user_info();
	}
	
	public function login () {
		$this->template = 'login/login';
		
		return array();
	}
	
	public function registration () {
		$this->template = 'login/register';
		
		return array();
	}	
}
