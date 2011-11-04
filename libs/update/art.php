<?php

class Update_Art extends Update_Abstract
{	
	protected $field_rights = array(
		'image' => 1
	);
	
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
}
