<?php

namespace App;

use System\EventListener\EventManager;
use System\Registry;

final class AppEvent
{
	/**
	 * @param EventManager $eventManager
	 * @return EventManager
	 */
	public function installEvents(EventManager $eventManager): EventManager
	{
		Registry::set(Registry::APP_EVENT, $eventManager);
		return $eventManager;
	}
}