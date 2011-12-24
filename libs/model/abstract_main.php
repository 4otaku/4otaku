<?php

abstract class Model_Abstract_Main extends Model_Abstract_Meta
{
	public function commit() {
		parent::commit();

		$this->log_version();

		return $this;
	}

	public function insert() {
		parent::insert();

		$this->log_version();

		return $this;
	}

	protected function log_version() {
		Database::insert('versions', array(
			'type' => 'post',
			'item_id' => $this->get_id(),
			'data' => base64_encode(serialize($this->get_data())),
			'time' => $this->get('sortdate'),
			'author' => sets::user('name'),
			'ip' => $_SERVER['REMOTE_ADDR']
		));
	}
}
