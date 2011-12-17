<?php

class Model_Post_Update_Link extends Model_Post_Link
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'update_id',
		'name',
		'size',
		'sizetype',
		'order',
	);

	// Название таблицы
	protected $table = 'post_update_link';
	
	protected function insert_link_urls($urls) {
		$order = 0;

		foreach ($urls as $url) {
			
			$url_id = $this->insert_url($url['url']);
		
			Database::insert('post_update_link_url', array(
				'url_id' => $url_id,
				'link_id' => $this->get_id(),
				'alias' => $url['alias'],
				'order' => $order,
			));
			$order++;
		}
	}
}
