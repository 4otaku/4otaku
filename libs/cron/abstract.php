<?php

// @TODO: Перенести сюда подсчет ушедшего времени
abstract class Cron_Abstract
{
	public function execute($function) {
		try {
			$this->$function();
		} catch (Error_Cron $e) {
			$mail = new mail();
			$mail->text(serialize($e))->send(def::notify('mail'));
		}
	}
}
