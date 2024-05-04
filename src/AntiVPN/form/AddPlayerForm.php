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

use AntiVPN\libs\form\SimpleForm;

use pocketmine\Server;

use pocketmine\player\Player;

class AddPlayerForm extends SimpleForm 
{
	
	const TARGET = 'player_name';
	
	public function __construct(Player $sender) 
	{
		parent::__construct 
		(
			function (Player $player, mixed $data) : void 
			{
				if (is_array($data))
				{
					if (isset($data[self::TARGET]) && trim($data[self::TARGET]) != '')
					{
						$username = trim($data[self::TARGET]);
						$target = Server::getInstance()->getPlayerExact($username);
						if ($target instanceof Player)
						{
							$username = $target->getName();
						}
						$form = new ConfirmAddWhiteListForm($username);
						$form->sendToPlayer($player);
					} 
				}
			}
		);
		$this->setTitle('§aAdd Player in §6Anti§cVPN§f Whitelist');
		$this->addLabel('§7When added, the AntiVPN system will ignore the player.');
		$this->addInput('Player name:', 'Exemple: ' . $sender->getName(), '', self::TARGET);
	}
	
}

?>