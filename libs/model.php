<?php

class Model
{
	public static function build($type, $data)
	{
		$class = 'Model_' . ucfirst($type);
		
		if (!class_exists($class)) {
			throw new Exception("Model $type not found");
		}
		
		$model = new $class($data);
		
		if (!($model instanceOf Model_Abstract)) {
			throw new Exception("Model $type must be instance of Model_Abstract");
		}		
		
		$keys = $model->get_key();
		
		foreach ($keys as $key) {
			if (empty($data[$key])) {
				$model->set_phantom();
			}
		}
		
		return $model;
	}
	
	public static function batch($type, $data)
	{
		$return = array();
		
		foreach ($data as $key => $item) {
			$return[$key] = self::build($type, $item);
		}
		
		return $return
	}	
}
