<?php

// @TODO: Перенести сюда подсчет ушедшего времени
abstract class Cron_Abstract
{
	public function execute($function) {
		if (is_callable(array($this, $function))) {
			try {
				$this->$function();
			} catch (Error_Cron $e) {
				$mail = new mail(def::notify('mail'));
				$mail->text(serialize($e))->send();
			}
		}
	}
}
