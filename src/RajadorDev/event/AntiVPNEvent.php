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

namespace RajadorDev\event;

use RajadorDev\AntiVPN;

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
	
	public function getManager() : AntiVPN
	{
		return AntiVPN::getInstance();
	}
	
	public function getIp() : String 
	{
		return $this->getPlayer()->getNetworkSession()->getIp();
	}
	
}
