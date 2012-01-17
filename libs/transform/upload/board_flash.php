<?php

class Transform_Upload_Board_Flash extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::board('flashsize');
	}
}
