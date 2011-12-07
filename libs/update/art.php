<?php

class Update_Art extends Update_Abstract
{	
	protected $field_rights = array(
		'image' => 1,
		'image_variation' => 1
	);
	
	// @TODO: перенести сюда коммит модели, как в Update_Post
	protected function save_changes() {}
	
	protected function image($data) {
		if (!is_numeric($data['id'])) {
			return;
		}
		
		$art = new Model_Art($data['id']);
		$art->set_array(array(
			'animated' => $data['image']['animated'],
			'extension' => $data['image']['extension'],
			'md5' => $data['image']['md5'],
			'resized' => $data['image']['resized'],
			'thumb' => $data['image']['thumb'],
		));
		
		$art->commit();
	}
	
	protected function image_variation($data) {
		$id = (int) $data['id'];
		$images = $data['image_variation'];
		$main_image = array_shift($images);
		
		if (empty($id) || empty($main_image)) {
			return;
		}
		
		$art = new Model_Art($id);
		$art->set_array(array(
			'animated' => $main_image['animated'],
			'extension' => $main_image['extension'],
			'md5' => $main_image['md5'],
			'resized' => $main_image['resized'],
			'thumb' => $main_image['thumb'],
		));
		
		if ($main_image['resized'] == 1) {
			$art->calculate_resize();
		}		
		
		$art->commit();
		
		$art->clear_similar();
		
		foreach ($images as $image) {
			$art->add_similar($image);
		}
	}
}
