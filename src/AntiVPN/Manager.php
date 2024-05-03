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

namespace AntiVPN;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\player\Player;

final class Manager extends PluginBase 
{
	
	/** @var Config **/
	private Config $whiteList;
	
	/** @var Config | null **/
	private ? Config $blackList = null;
	
	/** @var Manager **/
	private Manager $instance;
	
	/** @var bool **/
	private bool $hasBlackList = true;
	
	public static function getInstance() : self 
	{
		return self::$instance;
	}
	
	public function onLoad() : void 
	{
		self::$instance = $this;
	}
	
	public function onEnable() : void 
	{
		$this->getLogger()->info('Loading preferences...');
		$this->initResource();
		$this->loadPreferences();
		$this->getLogger()->info('Loading command...');
		$this->initCommand();
	}
	
	public function onDisable() : void 
	{
		if ($this->isBlackListEnabled())
		{
			$this->getBlackList()->save();
		}
	}
	
	private function initResource() : void 
	{
		$this->saveResource('config.yml', false);
	}
	
	private function loadPreferences() : void 
	{
		$hasBlackList = $this->getConfigValue('save-blacklist') == 'true';
		$this->hasBlackList = $hasBlackList;
		if ($hasBlackList)
		{
			$dir = $this->getDataFolder() . 'blacklist.txt';
			$this->blackList = new Config($dir, Config::ENUM);
			$this->getLogger()->info('Blacklist cache enabled.');
		} else {
			$this->getLogger()->info('Blacklist cache disabled.');
		}
	}
	
	private function initCommand() : void 
	{
		
	}
	
	public function getConfigValue(String $id, mixed $default) : mixed 
	{
		if ($this->getConfig()->exists($id))
		{
			return $this->getConfig()->get($id);
		}
		$this->getLogger()->alert('Config with id ' . $id . ' not found!');
		return $default;
	}
	
	public function isBlackListEnabled() : bool 
	{
		return $this->hasBlackList;
	}
	
	public function getBlackList() : ? Config 
	{
		return $this->hasBlackList;
	}
	
	public function getWhiteList() : Config 
	{
		return $this->whiteList;
	}
	
	public function isWhiteListed(Player | String $player) : bool 
	{
		if ($player instanceof Player)
		{
			$player = $player->getName();
		}
		return $this->whiteList->exists($player, true);
	}
	
	public function isBlacklisted(Player | String $player) : bool 
	{
		if ($player instanceof Player)
		{
			$player = $player->getName();
		}
		return $this->isBlackListEnabled() && $this->blackList->exists($player, true);
	}
	
}

?>