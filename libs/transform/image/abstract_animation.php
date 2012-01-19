<?php

abstract class Transform_Image_Abstract_Animation implements Transform_Image_Interface
{
	public function can_scale_animated() {
		return true;
	}
}
