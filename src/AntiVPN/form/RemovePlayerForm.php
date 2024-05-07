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

use AntiVPN\Manager;

use AntiVPN\libs\form\CustomForm;

class RemovePlayerForm extends CustomForm 
{
	
	const TARGET = 'player_name';
	
	public function __construct(int $default = 0)
	{
		parent::__construct 
		(
			function (Player $player, mixed $data) : void 
			{
				if (is_array($data))
				{
					if (isset($data[self::TARGET]))
					{
						$list = Manager::getInstance()->getWhiteList()->getAll(true);
						$id = $data[self::TARGET];
						if (isset($list[$id]))
						{
							$id = $list[$id];
							Manager::getInstance()->getWhiteList()->remove($id);
							$player->sendMessage("§7User $id §7removed §a§lSuceffully§r§7.");
						} else {
							$player->sendMessage('§cUser not found!');
						}
					}
				}
			}
		);
		
		$list = Manager::getInstance()->getWhiteList()->getAll(true);
		
		$this->setTitle('§cRemove §fWhitelisted §cPlayer');
		$this->addDropdown('§7Select the player:', $list, $default, self::TARGET);
	}
	
}