<?php

interface Cache_Interface_Single
{
	public function set ($key, $value, $expire);

	public function get ($key);

	public function delete ($key);

	public function increment ($key, $value);

	public function decrement ($key, $value);
}
