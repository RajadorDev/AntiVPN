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

namespace AntiVPN\command;

use AntiVPN\Manager;

use AntiVPN\utils\AntiVPNAPI;

use pocketmine\command\{Command, CommandSender};

final class AntiVPNCommand extends Command 
{
	
	const COMMAND_PREFIX = '§6Anti§cVPN§r  ';
	
	/** @var Manager **/
	private Manager $manager;
	
	public function __construct() 
	{
		$this->manager = Manager::getInstance();
		parent::__construct 
		(
			'antivpn',
			'AntiVPN manager command',
			'',
			['avpn', 'antvpn', 'antiproxy']
		);
		$this->setPermission('antivpn.command');
	}
	
	public function execute(CommandSender $p, String $label, array $args) 
	{
		if ($this->testPermission($p))
		{
			if (isset($args[0]) && trim($args[0]) != '')
			{
				switch (strtolower($args[0]))
				{
					case 'key':
					case 'secret':
					case 'setkey':
					case 'setsecret':
						if ($this->testPermission($p, 'antivpn.command.secret'))
						{
							if (isset($args[1]) && trim($args[1]) != '')
							{
								$key = $args[1];
								if (isset($args[2]))
								{
									$key = $args;
									unset($key[0]);
									$key = implode(' ', $key);
								}
								
								if (AntiVPNAPI::isValidKey($key))
								{
									$this->manager->setKey($key);
									$this->manager->saveKey();
									$p->sendMessage(self::COMMAND_PREFIX . '§7Key setted §l§aSuceffully§r§7.');
								} else {
									$p->sendMessage(self::COMMAND_PREFIX . "§7\"§f{$key}§7\" §cis not a valid key!");
								}
								
							} else {
								$p->sendMessage(self::COMMAND_PREFIX . "§7Use: §f/{$label} {$args[0]} <key>");
							}
						}
					break;
					default:
						$this->showUsageTo($p, $label);
					break;
				}
			} else {
				$this->showUsageTo($p, $label);
			}
		}
	}
	
	public function showUsageTo(CommandSender $p, String $label) : void 
	{
		$p->sendMessage(str_replace('{command_label}', $label, $this->getUsage()));
	}
	
}

?>