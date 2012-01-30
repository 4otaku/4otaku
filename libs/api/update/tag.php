<?php

class Api_Update_Tag extends Api_Update_Abstract
{
	protected $types = array(
		'none' => '',
		'character' => '00AA00',
		'series' => 'AA00AA',
		'author' => 'AA0000',
	);

	public function process() {

		$tag = $this->get('tag');
		$type = $this->get('type');

		if (empty($tag)) {
			throw new Error_Api('Пропущено обязательное поле: tag', Error_Api::MISSING_INPUT);
		}
		if (empty($type)) {
			throw new Error_Api('Пропущено обязательное поле: type', Error_Api::MISSING_INPUT);
		}
		if (!array_key_exists($type, $this->types)) {
			throw new Error_Api('Неправильно заполнено поле: type', Error_Api::INCORRECT_INPUT);
		}

		$color = $this->types[$type];

		$tag = trim(undo_safety($tag));
		$args = array($tag, '|' . $tag . '|', $tag);
		$exists = Database::get_field('tag', 'alias',
			'name = ? or locate(?, variants) or alias = ?', $args);

		if ($exists) {
			$alias = $exists;
		} else {
			$alias = Transform_Meta::make_alias($tag);
			Database::insert('tag', array(
				'alias' => $alias,
				'name'	=> $tag,
				'variants' => '|'
			));
			$this->add_error(Error_Api::UNKNOWN_TAG);
		}

		Database::update('tag', array('color' => $color), 'alias = ?', $alias);

		$this->set_success(true);
		$this->add_answer('tag', $alias);
	}
}
