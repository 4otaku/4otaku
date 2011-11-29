<?php

class Model_Post_Extra extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'name',
		'alias',
		'order',
	);

	// Название таблицы
	protected $table = 'post_extra';
}
