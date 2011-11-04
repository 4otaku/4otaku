<?php

class Create_Art extends Create_Abstract
{
	protected $field_rights = array(
		'transfer_to' => 1
	);
	
	public function main() {
		
		$this->set_redirect();
		$post = $this->correct_main_data(query::$post);
		
		if (!is_array($post['images'])) {
			engine::add_res('Не все обязательные поля заполнены.', true);
			return;
		}
		
		if (query::$url[2] == 'pool' && is_numeric(query::$url[3])) {
			$pool = new Model_Art_Pool(query::$url[3]);

			if (!$pool->correct_password($post['password'])) {
				engine::add_res('Неправильный пароль от группы.', true);
			}
		} else {
			$pool = false;
		}

		$worker = new Transform_Meta();
		
		$parsed_tags = $worker->parse_array($post['tags']);
		$tags = $worker->add_tags($parsed_tags);
		$category = $worker->category($post['category']);
		$parsed_author = $worker->parse($post['author'], def::user('author'));
		$author = $worker->author($parsed_author);

		$similar = $this->check_similar($post['images']);
		
		foreach ($post['images'] as $image_key => $image) {
			if (empty($image['tags'])) {
				$local_tags = $tags;
			} else {
				$parsed_tags = $worker->parse_array($image['tags']);
				$local_tags = $worker->add_tags($parsed_tags);
			}
			
			$art = new Model_Art();
			$art->set_array(array(
				'md5' => $image['md5'],
				'thumb' => $image['thumb'],
				'extension' => $image['extension'],
				'resized' => $image['resized'],
				'animated' => (int) $image['animated'],	
				'author' => $author,
				'category' => $category,
				'tag' => $local_tags,
				'source' => $post['source']
			));
			
			$art->insert();
			
			if (!empty($similar[$image_key])) {
				foreach ($similar[$image_key] as $item) {
					$art->add_similar($item);
				}
			}			

			if (!empty($pool)) {
				$pool->add_art($art);
			}			

			// TODO: перемести input__common::transfer в Model_Common
			if (!empty($post['transfer_to'])) {
				input__common::transfer(array(
					'sure' => 1, 
					'do' => array('art','transfer'), 
					'where' => $post['transfer_to'],
					'id' => $art->get_id()
				));
			}
		}

		if (count($post['images']) > 1) {
			engine::add_res('Ваши изображения успешно добавлены, и доступны в '.
				'<a href="/art/'.def::area(1).'/">очереди на премодерацию</a>.');
		} else {
			engine::add_res('Ваше изображение успешно добавлено, и доступно по адресу '.
				'<a href="/art/'.$art->get_id().'/">http://4otaku.ru/art/'.$art->get_id().'/</a> или в '.
				'<a href="/art/'.$def['area'][1].'/">очереди на премодерацию</a>.');
		}
	}
	
	protected function check_similar(&$images) {
		$similar = array();
		
		// Проверка на явное указание главного арта кнопкой "Объединить"
		foreach ($images as $image_key => $image) {
			if (!empty($image['master'])) {
				$master = $image_key;
				$similar[$master] = array();
				break;
			}
		}
		
		if (isset($master)) {
			foreach ($images as $image_key => $image) {
				if ($image_key == $master) {
					continue;
				} else {
					$similar[$master][] = $image;
					unset($images[$image_key]);
				}
			}
			
			return $similar;
		}
		
		
		// Если предыдущая проверка провалилась, 
		// то проверяем на создание групп из имен файлов
		$keys_by_id = array();
		foreach ($images as $image_key => $image) {
			if (
				!empty($image['id_in_group']) && 
				$image['id_in_group'] == 1
			) {
				$similar[$image_key] = array();
				$keys_by_id[$image['id_group']] = $image_key;
			}
		}
		
		if (!empty($keys_by_id)) {
			foreach ($images as $image_key => $image) {
				if (
					!empty($image['id_in_group']) && 
					$image['id_in_group'] > 1 && 
					array_key_exists($image['id_group'], $keys_by_id)
				) {
					$master = $keys_by_id[$image['id_group']];
					
					$similar[$master][] = $image;
					unset($images[$image_key]);
				}			
			
			}
			
		}		
			
		return $similar;
	}
	
	protected function correct_main_data($data) {
		if (empty($data['password'])) {
			$data['password'] = '';
		}
		
		if (empty($data['tags'])) {
			$data['tags'] = '';
		}	
		
		return $data;
	}
}