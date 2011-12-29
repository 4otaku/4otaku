<?php

if (!DEBUG) {
	function exception_handler($exception) {
		$mail = new mail();
		$mail->text(serialize(query::$url) . serialize($exception))
			->send(def::notify('mail'));
	}

	set_exception_handler('exception_handler');

	function shutdown_handler() {
		$error = error_get_last();
		if ($error && ($error['type'] == E_ERROR || $error['type'] == E_PARSE || $error['type'] == E_COMPILE_ERROR)) {
			if (strpos($error['message'], 'Allowed memory size') === 0) {
				ob_end_clean();
				$mail = new mail();
				$mail->text(serialize(query::$url) . serialize($error))
					->send(def::notify('mail'));
			} else {
				ob_end_clean();
				$mail = new mail();
				$mail->text(serialize(query::$url) . serialize($error))
					->send(def::notify('mail'));
			}
		}
	}

	register_shutdown_function('shutdown_handler');
} else {
	if (isset($_GET['phpinfo'])) {
		phpinfo();
		die;
	}
}
