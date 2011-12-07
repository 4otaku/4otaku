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
			$this->save_changes();
			
			Database::commit();
		} catch (PDOException $e) {
			
			Database::rollback();
		}
		
	}
	
	abstract protected function save_changes();
}
