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

use pocketmine\event\Event;

class FinishCheckEvent extends Event 
{
	
	public function __construct(protected String $ip, protected String $username, private bool $isSafe) {}
	
	public function getUsername() : String 
	{
		return $this->username;
	}
	
	public function getIp() : String 
	{
		return $this->ip;
	}
	
	public function isSafe() : bool 
	{
		return $this->isSafe;
	}
	
}

?>