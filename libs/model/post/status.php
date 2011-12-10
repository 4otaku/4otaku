<?php

class Model_Post_Status extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'overall',
		'total',
		'broken',
		'partially_broken',
		'unmirorred',
		'unknown',
		'uncheked',
		'lastcheck',
	);

	// Название таблицы
	protected $table = 'post_status';
	
	const 
		COEFF_BROKEN = 9900,
		COEFF_PARTIALLY_BROKEN = 100,
		COEFF_UNMIRRORED = 20,
		COEFF_UNKNOWN = 5,
		COEFF_UNCHECKED = 1;
	
	public function calculate($links = null) {
		
		if ($links === null) {
			$links = $this->get_links();
		}
		
		$total = 0;
		$broken = 0;
		$partially_broken = 0;
		$unmirorred = 0;
		$unknown = 0;
		$uncheked = 0;
		
		foreach ($links as $link) {
			if (!($link instanceOf Model_Post_Link)) {
				continue;
			}
			
			$url = $link->get('url');
			
			$total++;
			if ($this->is_broken($url)) {
				$broken++;
			}
			
			if ($this->is_partially_broken($url)) {
				$partially_broken++;
			}
			
			if ($this->is_unmirorred($url)) {
				$unmirorred++;
			}
			
			if ($this->is_unknown($url)) {
				$unknown++;
			}
			
			if ($this->is_uncheked($url)) {
				$uncheked++;
			}
		}
		
		$this->set('total', $total);
		$this->set('broken', $broken);
		$this->set('partially_broken', $partially_broken);
		$this->set('unmirorred', $unmirorred);
		$this->set('unknown', $unknown);
		$this->set('uncheked', $uncheked);
		
		$this->set('overall', $this->calculate_overall());
		
		return $this;
	}
	
	public function calculate_overall() {
		$total = $this->get('total');
		$broken = $this->get('broken');
		$partially_broken = $this->get('partially_broken');
		$unmirorred = $this->get('unmirorred');
		$unknown = $this->get('unknown');
		$uncheked = $this->get('uncheked');
		
		$overall = $broken * self::COEFF_BROKEN + 
			$partially_broken * self::COEFF_PARTIALLY_BROKEN + 
			$unmirorred * self::COEFF_UNMIRRORED + 
			$unknown * self::COEFF_UNKNOWN + 
			$uncheked * self::COEFF_UNCHECKED;
			
		return $overall / $total;
	}
	
	protected function get_links() {
		$id = $this->get_id();
		
		$post = new Model_Post($id);
		
		$links = Database::join('post_link_url', 'plu.link_id = pl.id')
			->join('post_url', 'plu.url_id = pu.id')
			->get_full_table('post_link', 'pl.post_id = ?', $id);

		foreach ($links as $link) {
			$link = new Model_Post_Link($link);
			$post->add_link($link);
		}
		
		return $post->get('link');
	}
	
	protected function is_broken($urls) {
		foreach ($urls as $url) {
			if ($url['status'] != 3) {
				return false;
			}
		}
		
		return true;
	}
	
	protected function is_partially_broken($urls) {
		foreach ($urls as $url) {
			if ($url['status'] == 3) {
				return true;
			}
		}
		
		return false;
	}
	
	protected function is_unmirorred($urls) {
		$working = 0;
		foreach ($urls as $url) {
			if ($url['status'] != 3) {
				$working++;
			}
			if ($working > 1) {
				return false;
			}
		}
		return (bool) $working;
	}
	
	protected function is_unknown($urls) {
		foreach ($urls as $url) {
			if ($url['status'] == 2) {
				return true;
			}
		}
		
		return false;		
	}
	
	protected function is_uncheked($urls) {
		foreach ($urls as $url) {
			if ($url['status'] == 0) {
				return true;
			}
		}
		
		return false;		
	}
}
