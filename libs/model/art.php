<?php

class Model_Art extends class Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'md5',
		'thumb',
		'extension',
		'resized',
		'animated',
		'author',
		'category',
		'tag',
		'rating',
		'translator',
		'source',
		'comment_count',
		'last_comment',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	private $table = 'art';	
}
