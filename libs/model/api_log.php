<?php

class Model_Api_Log extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'uid',
		'ip',
		'type',
		'data',
		'date'
	);

	// Название таблицы
	protected $table = 'api_log';
}
