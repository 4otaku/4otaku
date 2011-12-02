<?php

class Read_Edit_Post extends Read_Edit_Abstract
{
	
	protected function title($url) {
		if (!Check::id($url[2])) {
			throw new Error_Read_Edit();
		}
		
		$this->data['title'] = Database::get_field('post', 'title', $url[2]);
	}
}
