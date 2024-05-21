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

namespace RajadorDev\form;

use RajadorDev\libs\form\ModalForm;

use pocketmine\player\Player;

abstract class ConfirmForm extends ModalForm 
{
	
	public function __construct(String $title, String $description, String $buttonConfirmText, String $buttonDeclineText) 
	{
		parent::__construct 
		(
			function (Player $player, bool $value) : void 
			{
				if ($value)
				{
					$this->onConfirm($player);
				}
			}
		);
		$this->setTitle($title);
		$this->setContent($description);
		$this->setButton1($buttonConfirmText);
		$this->setButton2($buttonDeclineText);
	}
	
	abstract public function onConfirm(Player $player) : void;
	
}