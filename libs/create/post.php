<?php

class Create_Post extends Create_Abstract
{
	protected $field_rights = array(
		'transfer_to' => 1
	);
	
	protected $function_rights = array(
		'update' => 1
	);	
	
	public function update() {

		$this->set_redirect();
		
		if (!is_numeric(query::$post['id'])) {
			engine::add_res('Что-то странное с формой обновления, сообщите администрации', true);
			return;			
		}
		
		$author = trim(strip_tags(query::$post['author']));
		if (empty($author)) {
			engine::add_res('Вы забыли указать автора обновления', true);
			return;
		}
		
		$text = Transform_Text::format(query::$post['text']);
		if (!trim(strip_tags($text))) {
			engine::add_res('Вы забыли добавить описание обновления', true);
			return;			
		}		
		
		$links = array();
		foreach (query::$post['link'] as $link) {
			if (!empty($link['use'])) {
				unset($link['use']);
				$links[] = $link;
			}
		}
		
		$links = Transform_Link::similar(Transform_Link::parse($links));
		foreach ($links as $link) {
			if (count(array_filter($link['url'])) > 0) {
				$links_found = true;
				break;
			}
		}
		if (empty($links_found)) {
			engine::add_res('Проверьте ссылки, с ними была какая-то проблема', true);
			return;			
		}
		
		$update = new Model_Post_Update(array(
			'post_id' => query::$post['id'],
			'username' => $author,
			'text' => $text,
			'pretty_text' => undo_safety(query::$post['text']),
			'link' => serialize($links)
		));
		$update->insert();
	
		engine::add_res('Запись успешно обновлена');
	}
}
