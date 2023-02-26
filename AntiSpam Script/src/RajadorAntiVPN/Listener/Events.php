<?php

namespace RajadorAntiVPN\Listener;

use pocketmine\event\Listener as EventsListener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;

use RajadorAntiVPN\System;

class Events implements EventsListener {
	
	public function __construct(System $plugin){
		$this->plugin = $plugin;
	}
	
	public function onPre(PlayerPreLoginEvent $ev)
	{
		if(!$ev->isCancelled() and $this->plugin->isBlocked($ev->getPlayer()))
		{
		$ev->setKickMessage($this->plugin->getConfigData()['kick-msg']);
		$ev->setCancelled(true);
		}
		
	}
	
	public function onJoin(PlayerJoinEvent $ev)
	{
		if(!$this->plugin->isAllowed(($p = $ev->getPlayer())) and !$this->plugin->isChecking($p))
		$this->plugin->startCheckVPN($ev->getPlayer($p));
		
	}
	
	
	
}
