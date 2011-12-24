<?php

// @TODO: Перенести сюда подсчет ушедшего времени
abstract class Cron_Abstract 
{
	public function execute($function) {
		try {
			$this->$function();
		} catch (Error_Cron $e) {
			// @TODO: Добавить отсылку на админское мыло
		}
	}
}
