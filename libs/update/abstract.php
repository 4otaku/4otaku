<?php

abstract class Update_Abstract extends Action_Abstract
{
	public function main() {

		Database::begin();

		try {

			$data = $this->reader->get_data();

			foreach ($data as $key => $value) {
				if (method_exists($this, $key)) {
					$this->$key($data);
				}
			}

			if (
				!empty($data['edit']) &&
				!array_key_exists($data['edit'], $data) &&
				method_exists($this, $data['edit'])
			) {
				$function = $data['edit'];
				$this->$function($data);
			}

			$this->save_changes();

			Database::commit();
		} catch (PDOException $e) {

			Database::rollback();
		}

	}

	abstract protected function save_changes();
}
