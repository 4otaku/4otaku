<?

class Item_Board extends Item_Abstract_Meta implements Plugins
{
	// TODO: унылый хак, решить. См Board_Output::add_needed_content
	public $data = array();
	
	protected function get_downloads () {
		$return = array('pdf' => true);

		if (class_exists('ZipArchive')) {
			$count = $this->inner_get('count');

			if ($count['image'] + $count['flash'] > 1) {
				$return['zip'] = true;
			}
		}
		
		return $return;
	}
	
	protected function get_count () {
		$count = array('image' => 0, 'video' => 0, 'flash' => 0);
		
		if (!empty($this->data['posts'])) {
			foreach ($this->data['posts'] as $post) {
				foreach ($count as $key => &$value) {
					$value += isset($post['content'][$key]) ?
						count($post['content'][$key])
						: 0;
				}
			}
		}
		
		return $count;
	}	
}
