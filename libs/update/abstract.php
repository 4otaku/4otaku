<?php

abstract class Update_Abstract extends Abstract_Action
{	
	public function main() {
		
		foreach (query::$post as $key => $value) {
			
			if (method_exists($this, $key)) {
				$this->$key(query::$post);
			}
		}
		
	}
}
