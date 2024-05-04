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

namespace AntiVPN\form;

use AntiVPN\Manager;

use pocketmine\player\Player;

class ConfirmAddWhiteListForm extends ConfirmForm 
{
	
	public function __construct(private String $username) 
	{
		parent::__construct 
		(
			'§aAdd §f' . $username . ' §ato §fWhiteList',
			'§7Are you sure to add §f' . $username . '§7to §fWhiteList§7?',
			'§aYes',
			'§cNot'
		);
	}
	
	public function onConfirm(Player $player) : void 
	{
		Manager::getInstance()->addWhitelisted($this->username);
		$player->sendMessage('§7Player: §f' . $this->username . ' §7added §a§lSuceffully§r§7.');
	}
	
}

?>