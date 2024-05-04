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

use AntiVPN\event\{StartCheckEvent, PlayerBlockedEvent, FinishCheckEvent};

use AntiVPN\utils\AntiVPNAPI;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerLoginEvent;

final class EventsListener implements Listener 
{
	
	/** @param Manager **/
	public function __construct(private Manager $manager) 
	{
		$this->manager = $manager;
		$manager->getLogger()->info('Registering listener...');
		$manager->getServer()->getPluginManager()->registerEvents($this, $manager);
	}
	
	/**
	 * @priority HIGHEST
 	**/
	public function checkVpn(PlayerLoginEvent $e) : void 
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
						$this->manager->startCheck($player, AntiVPNAPI::getDefaultProcess());
					}
				} else {
					$this->manager->startCheck($player, AntiVPNAPI::getDefaultProcess());
				}
			} else {
				$this->manager->startCheck($player, AntiVPNAPI::getDefaultProcess());
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
	public function start(StartCheckEvent $e) : void 
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
	
	/**
	 * @priority MONITOR
 	**/
	public function addProcess(StartCheckEvent $e) : void
	{
		if (!$e->isCancelled())
		{
			$this->manager->addInProcess($e->getIp());
		}
	}
	
	/**
	 * @priority MONITOR
 	**/
	public function finish(FinishCheckEvent $e) : void 
	{
		$this->manager->removeFromProcess($e->getIp());
	}
	
}

?>