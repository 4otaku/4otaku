<?

class Item_Board extends Item_Abstract_Container implements Plugins
{	
	public function postprocess () {

		parent::postprocess();
	
		if (!empty($this->data['content']['video'])) {
			foreach ($this->data['content']['video'] as & $video) {

				$video['object'] = str_replace(
					array('%video_width%','%video_height%'),
					Config::settings('video_size'),
					$video['object']
				);				
			}
		}
	}	
		
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
		
		foreach ($this->data['content'] as $key => $data) {
			$count[$key] = count($data);
		}
		
		if (!empty($this->data['totals'])) {
			
			foreach ($this->data['totals'] as $key => $total) {
				if (isset($count[$key])) {
					$count[$key] += $total;
				}
			}			
		} elseif (!empty($this->data['posts'])) {
			
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
	
	protected function get_skipped () {

		if (empty($this->data['totals']) || empty($this->data['items'])) {
			return false;
		}
		
		$skipped = $this->data['totals'];

		foreach ($skipped as $key => & $total) {
			
			if ($key == 'post') {
				$total = $total - count($this->data['items']);
			} else {
				foreach ($this->data['items'] as $post) {
					if (!empty($post['content'][$key])) {
						$total = $total - count($post['content'][$key]);
					}
				}
			}
			
			if ($total < 1) {
				unset($skipped[$key]);
			}
		}
		
		return $skipped;
	}
	
	protected function get_short_text () {
		$cut_length = (bool) $this->data['thread'] ?
			Config::settings('thread_preview_length') :
			Config::settings('post_preview_length');
		
		return Transform_Text::cut_long_text(
			$this->data['text'],
			$cut_length,
			Config::settings('preview_end'),
			Config::settings('word_cut_length')
		);
	}
}
