<?

class Footer_Output extends Output implements Plugins
{
	public function main () {

		$this->items['year'] = date('Y');
	}
}
