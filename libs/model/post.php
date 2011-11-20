<?php

class Model_Post extends Model_Abstract_Meta
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
		'comment_count',
		'last_comment',
		'update_count',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'post';
	
	protected $meta_fields= array('tag', 
		'category', 'author', 'language');
}
