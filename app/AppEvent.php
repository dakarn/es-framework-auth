<?php

namespace App;

use System\EventListener\EventManager;
use System\ES;

final class AppEvent
{
	/**
	 * @param EventManager $eventManager
	 * @return EventManager
	 */
	public function installEvents(EventManager $eventManager): EventManager
	{
		ES::set(ES::APP_EVENT, $eventManager);
		return $eventManager;
	}
}