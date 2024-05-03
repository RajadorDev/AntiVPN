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

namespace AntiVPN\task;

use pocketmine\scheduler\AsyncTask;

class CheckTask extends AsyncTask 
{
	
	/** @var String **/
	protected String $ip, $username, $token;
	
	/**
	 * @param String $ip 
	 * @param String $user
	 * @param String $token
 	**/
	public function __construct(String $ip, String $user; String $token) 
	{
		$this->ip = $ip;
		$this->username = $user;
		$this->token = $token;
	}
	
}

?>