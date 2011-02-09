<?

class Default_Controller extends Controller
{
	public function build() {
		$this->query->agent = Globals::$user['agent'];
	}
}
