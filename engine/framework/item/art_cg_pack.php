<?

class Item_Art_Cg_pack extends Item_Abstract_Marked implements Plugins
{	
	protected $text_replacements = array(
		'/\[url=(.+?)\](.+?)\[\/url\]/' => '$2: $1',
		'/\[.+?\](.+?)\[\/.+?\]/' => '$1',
		'/\n/' => '<br />',
	);	
	
	public function postprocess () {
		
		$headline = trim(htmlspecialchars($this->data['pretty_text']));
		$from = array_keys($this->text_replacements);
		$to = array_values($this->text_replacements);
		
		$this->data['headline'] = preg_replace($from, $to, $headline);
	}
}
