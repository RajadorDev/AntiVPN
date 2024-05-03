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

use pocketmine\command\{Command, CommandSender};

final class AntiVPNCommand extends Command 
{
	
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
		
	}
	
}

?>