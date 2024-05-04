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

namespace AntiVPN\event;

use AntiVPN\Manager;

use pocketmine\player\Player;

use pocketmine\event\player\PlayerEvent;

abstract class AntiVPNEvent extends PlayerEvent 
{
	
	/**
	 * @param Player $player
 	**/
	public function __construct(Player $player) 
	{
		$this->player = $player;
	}
	
	public function getManager() : Manager 
	{
		return Manager::getInstance();
	}
	
	public function getIp() : String 
	{
		return $this->getPlayer()->getNetworkSession()->getIp();
	}
	
}

?>