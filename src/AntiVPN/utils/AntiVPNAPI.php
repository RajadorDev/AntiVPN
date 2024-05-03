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

namespace AntiVPN\utils;

final class AntiVPNAPI 
{
	
	const URL = 'https://vpnapi.io/api/';
	
	const CHECK_INDEX = 'security';
	
	const CHECK_ERROR_INDEX = 'message';
	
	public static function isValidKey(String $key) : bool 
	{
		return strlen(trim($key)) > 1 && strpos($key, ' ') === false;
	}
	
	public static function makeUrl(String $adr, String $token) : String 
	{
		return self::API . $adr . '?key=' . $token;
	}
	
}

?>