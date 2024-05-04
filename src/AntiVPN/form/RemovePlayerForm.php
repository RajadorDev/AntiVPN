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

use pocketmine\player\Player;

use AntiVPN\libs\form\CustomForm;

class RemovePlayerForm extends CustomForm 
{
	
	public function __construct(int $default = 0)
	{
		parent::__construct 
		(
			function (Player $player, mixed $data) : void 
			{
				
			}
		);
		
		$list = Manager::getInstance()->getWhiteList()->getAll(true);
		
		$this->setTitle('§cRemove §fWhitelisted §cPlayer');
		$this->addDropdown('§7Select the player:', $list);
	}
	
}

?>