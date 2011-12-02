<?php

class Error_Read_Edit extends Error_Read 
{
	const DEFAULT_MESSAGE = 'Некорректные данные редактирования';
	
	public function __construct($message = '', $code = 0, $previous = NULL) {
		if (empty($message)) {
			$message = self::DEFAULT_MESSAGE;
		}
		
		parent::__construct($message, $code, $previous);
	}
}
