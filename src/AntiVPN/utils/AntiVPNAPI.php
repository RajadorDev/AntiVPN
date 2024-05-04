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

use AntiVPN\Manager;

use AntiVPN\event\PlayerBlockedEvent;

use pocketmine\Server;

use pocketmine\player\Player;

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
	
	public static function getDefaultProcess() : callable 
	{
		return function (String $username, String $ip, ? Player $player, bool $isSafe) : void 
		{
			if (Manager::getInstance()->isCacheEnabled())
			{
				Manager::getInstance()->addCachedValue($ip, $isSafe);
			}
			if ($player instanceof Player)
			{
				$username = $player->getName();
				$player->kick('IP proxy/vpn detected', null, Manager::getInstance()->getKickScreenMessage($username));
				$message = Manager::getInstance()->getAdminAlertMessage($username);
				foreach (Server::getInstance()->getPlayers() as $all)
				{
					if ($all->hasPermission('antivpn.alert.receive'))
					{
						$all->sendMessage($message);
					}
				}
			}
		};
	}
	
	public static function isProxyOrVpn(array $data) : bool 
	{
		return $data['vpn'] || $data['proxy'];
	}
	
}

?>