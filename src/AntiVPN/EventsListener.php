<?php

declare (strict_types = 1);

/*
  
  Rajador Developer

  ▒█▀▀█ ░█▀▀█ ░░░▒█ ░█▀▀█ ▒█▀▀▄ ▒█▀▀▀█ ▒█▀▀█ 
  ▒█▄▄▀ ▒█▄▄█ ░▄░▒█ ▒█▄▄█ ▒█░▒█ ▒█░░▒█ ▒█▄▄▀ 
  ▒█░▒█ ▒█░▒█ ▒█▄▄█ ▒█░▒█ ▒█▄▄▀ ▒█▄▄▄█ ▒█░▒█

  GitHub: https://github.com/RajadorDev

  Discord: rajadortv


*/

namespace AntiVPN;

use AntiVPN\event\{StartCheckEvent, PlayerBlockedEvent};

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerLoginEvent;

final class EventsListener implements Listener 
{
	
	/** @var Manager **/
	private Manager $manager;
	
	public function __construct(Manager $manager) 
	{
		$this->manager = $manager;
		$manager->getLogger()->info('Registering listener...');
		$manager->getPluginManager()->registerEvents($this, $manager);
	}
	
	/**
	 * @priority HIGHEST
 	**/
	public function checkVpn(PlayerLoginEvent $e)
	{
		if (!$e->isCancelled())
		{
			$player = $e->getPlayer();
			if ($this->isCacheEnabled())
			{
				if ($this->manager->inCache($player))
				{
					if (!$this->manager->getCacheValue($player))
					{
						$e->cancel();
						$e->setKickMessage($this->manager->getKickScreenMessage($player->getName()));
					} else {
						$this->manager->startCheck($player);
					}
				} else {
					$this->manager->startCheck($player);
				}
			} else {
				$this->manager->startCheck($player);
			}
			
			if ($e->isCancelled())
			{
				$event = new PlayerBlockedEvent($player);
				$event->call();
				if ($event->isCancelled())
				{
					$e->uncancel();
				}
			}
			
		}
	}
	
	/**
	 * @priority LOW
 	**/
	public function start(StartCheckEvent $e) 
	{
		if (!$e->isCancelled())
		{
			$player = $e->getPlayer();
			if ($this->manager->isWhiteListed($player) || $player->hasPermission('antivpn.bypass'))
			{
				$e->cancel();
				$this->manager->getLogger()->info('Player ' . $player->getName() . ' was allowed to join by whitelist.');
			}
		}
	}
	
}

?>