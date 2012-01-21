<?php

abstract class Action_Abstract
{
	protected $need_redirect = false;
	protected $redirect_address = '';

	protected $minimal_rights = 0;

	protected $field_rights = array();
	protected $function_rights = array();

	public function __construct(Action_Reader_Abstract $reader = null,
		Action_Writer_Abstract $writer = null) {

		if ($reader === null) {
			$reader = new Action_Reader_User();
		}

		if ($writer === null) {
			$writer = new Action_Writer_User();
		}

		$this->reader = $reader;
		$this->writer = $writer;
	}

	public function check_access($function) {

		$data = $this->reader->get_data();

		if (sets::user('rights') < $this->minimal_rights) {
			return false;
		}

		if (
			array_key_exists($function, $this->function_rights) &&
			sets::user('rights') < $this->function_rights[$function]
		) {
			return false;
		}

		foreach ($data as $key => $item) {
			if (
				array_key_exists($key, $this->field_rights) &&
				sets::user('rights') < $this->field_rights[$key]
			) {
				return false;
			}
		}

		return true;
	}

	public function process_result() {
		$data = $this->writer->return_data();
		$this->writer->process_actions();

		return $data;
	}
}
