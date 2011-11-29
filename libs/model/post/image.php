<?php

class Model_Post_Image extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'file',
		'extension',
		'width',
		'height',
		'weight',
		'order',
	);

	// Название таблицы
	protected $table = 'post_image';
}
