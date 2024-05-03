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

use pocketmine\event\Listener;

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
			if ($this->isBlackListEnabled())
			{
				if ($this->manager->isBlacklisted($player))
				{
					$e->cancel();
					$e->setKickMessage($this->manager->getKickScreenMessage($player->getName()));
				} else {
					$this->manager->startCheck($player);
				}
			} else {
				$this->manager->startCheck($player);
			}
		}
	}
}

?>