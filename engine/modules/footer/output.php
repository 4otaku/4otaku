<?

class Footer_Output extends Output implements Plugins
{
	public function main () {

		return array('year' => date('Y'));
	}
}
