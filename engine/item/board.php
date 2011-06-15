<?

class Item_Board extends Item_Abstract_Container implements Plugins
{	
	const BOARD_LINK_PREFIX = '_board_link_prefix_';
	
	protected $links = array();
	protected $link_regexp = '/&gt;&gt;(\d+)(\s|$|<br[^>]*>)/s';
	
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
		
		if (preg_match_all($this->link_regexp, $this->data['text'], $links)) {
			Cache::$prefix = self::BOARD_LINK_PREFIX;
			
			$links = array_unique($links[1]);
			
			$found_links = Cache::get_array($links);	
			$not_found_links = array_diff($links, array_keys($found_links));

			if (!empty($not_found_links)) {
				$condition = Database::array_in('id', $not_found_links);
				$condition .= " and `area` != 'deleted'";
				
				$not_found_links = Database::get_vector(
					'board', 
					array('id', 'thread', 'meta'), 
					$condition, 
					$not_found_links
				);
				
				foreach ($not_found_links as & $link) {
					preg_match_all('/category__([a-z]+)/i', $link['meta'], $categories);
					$link['board'] = empty($categories[1]) ? '' : 
						$categories[1][array_rand($categories[1])].'/';
					unset($link['meta']);
				}
				unset($link);

				Cache::set_array(
					array_keys($not_found_links), 
					array_values($not_found_links), 
					DAY
				);
			}
			
			$this->links = array_replace($found_links, $not_found_links);

			$this->data['text'] = preg_replace_callback(
				$this->link_regexp, array($this, 'set_link'), $this->data['text']);
		}
	}

	protected function set_link ($id) {

		if (isset($this->links[$id[1]])) {
			
			$link = $this->links[$id[1]];

			return '<a href="/board/'.$link['board'].'thread/'.
				($link['thread'] ? $link['thread'] : $id[1]).
				'#board-'.$id[1].'">&gt;&gt;'.$id[1].'</a>'.$id[2];
		}
			
		return $id[0];
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
