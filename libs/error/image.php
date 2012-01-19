<?php

class Error_Image extends Error_Upload
{
	const
		EMPTY_FILE = 5,
		FILE_TOO_LARGE = 10,
		ALREADY_EXISTS = 30,
		CANT_SCALE_ANIMATED = 150,
		UNEXPECTED_FUNCTION_CALL = 180;
}
