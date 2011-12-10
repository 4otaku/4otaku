<?php

class Model_Post_Update_Status extends Model_Post_Status
{
	// Название таблицы
	protected $table = 'post_update_status';
	
	protected function get_links() {
		$id = $this->get_id();
		
		$update = new Model_Post_Update($id);
		
		$links = Database::join('post_update_link_url', 'pulu.link_id = pul.id')
			->join('post_url', 'pulu.url_id = pu.id')
			->get_full_table('post_update_link', 'pul.update_id = ?', $id);
		
		foreach ($links as $link) {
			$link = new Model_Post_Update_Link($link);
			$update->add_link($link);
		}
		
		return $update->get('link');
	}
}
