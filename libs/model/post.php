<?php

class Model_Post extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'text',
		'pretty_text',
		'image',
		'link',
		'info',
		'file',
		'author',
		'category',
		'language',
		'tag',
		'update_count',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'post';
}
