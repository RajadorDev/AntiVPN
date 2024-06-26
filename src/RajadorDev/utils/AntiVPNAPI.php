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

namespace RajadorDev\utils;

use RajadorDev\AntiVPN;

use RajadorDev\event\PlayerBlockedEvent;

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
		return self::URL . $adr . '?key=' . $token;
	}
	
	public static function getDefaultProcess() : callable 
	{
		return function (String $username, String $ip, ? Player $player, bool $isSafe) : void 
		{
			if (AntiVPN::getInstance()->isCacheEnabled())
			{
				AntiVPN::getInstance()->addCachedValue($ip, $isSafe);
			}
			if (!$isSafe && $player instanceof Player)
			{
				$username = $player->getName();
				$player->kick('IP proxy/vpn detected', null, AntiVPN::getInstance()->getKickScreenMessage($username));
				$message = AntiVPN::getInstance()->getAdminAlertMessage($username);
				foreach (Server::getInstance()->getOnlinePlayers() as $all)
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