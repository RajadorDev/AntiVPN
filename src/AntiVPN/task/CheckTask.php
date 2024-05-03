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

use CurlHandle;

use AntiVPN\utils\AntiVPNAPI;

use pocketmine\scheduler\AsyncTask;

class CheckTask extends AsyncTask 
{
	
	const FAIL_CURL_INIT = 0;
	
	const FAIL_CURL_EXECUTE = 1;
	
	const FAIL_API = 2;
	
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
	
	public function onRun() : void
	{
		$url = AntiVPNAPI::makeUrl($this->ip, $this->token);
		$curl = curl_init($url);
		if ($curl instanceof CurlHandle)
		{
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, false);
		} else {
			$this->setResult(self::FAIL_CURL);
		}
	}
	
}

?>