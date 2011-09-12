<?php

interface Cache_Interface_Array
{
	public function set_array ($keys, $values, $expire);

	public function get_array ($keys);

	public function delete_array ($keys);

	public function increment_array ($keys, $value);

	public function decrement_array ($keys, $value);
}
