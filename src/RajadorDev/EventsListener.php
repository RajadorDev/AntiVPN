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

namespace RajadorDev;

use RajadorDev\event\{StartCheckEvent, PlayerBlockedEvent, FinishCheckEvent};

use RajadorDev\utils\AntiVPNAPI;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerLoginEvent;

final class EventsListener implements Listener 
{
	
	/** @param Manager $manager **/
	public function __construct(private Manager $manager) 
	{
		$manager->getLogger()->debug('Registering listener...');
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
			if ($this->manager->isCacheEnabled())
			{
				if ($this->manager->inCache($player))
				{
					$playerName = $player->getName();
					if (!$this->manager->getCacheValue($player))
					{
						$e->cancel();
						$e->setKickMessage($this->manager->getKickScreenMessage($playerName));
						$this->manager->getLogger()->debug("Player $playerName is BLOCKED by Cache.");
					} else {
						$this->manager->getLogger()->debug("Player $playerName is allowed by Cache.");
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
			if ($this->manager->isWhiteListed($player))
			{
				$e->cancel();
				$this->manager->getLogger()->info('Player ' . $player->getName() . ' was allowed to join by whitelist.');
			} else if ($player->hasPermission('antivpn.bypass')) {
				$e->cancel();
				$this->manager->getLogger()->info('Player ' . $player->getName() . ' was allowed to join by bypass permission.');
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
			$this->manager->getLogger()->debug('Checking ' . $e->getIp() . ' ...');
		}
	}
	
	/**
	 * @priority MONITOR
 	**/
	public function finish(FinishCheckEvent $e) : void 
	{
		$ip = $e->getIp();
		$this->manager->removeFromProcess($ip);
		if (!$e->isSafe())
		{
			$this->manager->getLogger()->notice('Address: ' . $ip . ' of ' . $e->getUsername() . ' is not secure.');
		} else {
			$this->manager->getLogger()->debug('Address: ' . $ip . ' is secure.');
		}
	}
	
}