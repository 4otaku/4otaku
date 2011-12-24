<?php

abstract class Update_Abstract extends Abstract_Action
{
	public function main() {

		Database::begin();

		try {

			foreach (query::$post as $key => $value) {
				if (method_exists($this, $key)) {
					$this->$key(query::$post);
				}
			}

			if (
				!empty(query::$post['edit']) &&
				!array_key_exists(query::$post['edit'], query::$post) &&
				method_exists($this, query::$post['edit'])
			) {
				$function = query::$post['edit'];
				$this->$function(query::$post);
			}

			$this->save_changes();

			Database::commit();
		} catch (PDOException $e) {

			Database::rollback();
		}

	}

	abstract protected function save_changes();
}
