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

use pocketmine\player\Player;

class WhiteListMainForm extends SimpleForm 
{
	
	const ADD = 'add_player';
	
	const REMOVE = 'remove_player';
	
	const LIST = 'see_list';
	
	public function __construct() 
	{
		parent::__construct 
		(
			function (Player $player, mixed $data) : void 
			{
				switch ($data)
				{
					case self::ADD:
						
					break;
					case self::REMOVE:
						
					break;
					case self::LIST:
						
					break;
				}
			}
		);
		$this->setTitle('§6Anti§cVPN §fWhiteList');
		$this->setContent('§7Select a option bellow:');
		foreach 
		(
			[
				self::ADD => ['§aAdd Player', 'textures/gui/newgui/Friends.png'],
				self::REMOVE => ['§cRemove Player', 'textures/gui/newgui/X.png'],
				self::LIST => ['§eList', 'textures/gui/newgui/Realms.png']
			] as $id => $data
		)
		{
			$this->addButton($data[0], self::IMAGE_TYPE_PATH, $data[1], $id);
		}
	}
	
}

?>