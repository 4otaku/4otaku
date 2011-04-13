<?

class Footer_Output extends Output implements Plugins
{
	public function process () {

		return array('year' => date('Y'));
	}
}
