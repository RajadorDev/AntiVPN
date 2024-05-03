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
	}
	
	public function onDisable() : void 
	{
		
	}
	
	public function initResource() : void 
	{
		$this->saveResource('config.yml', false);
	}
	
	private function loadPreferences() : void 
	{
		$hasBlackList = $this->getConfigValue('save-blacklist') == 'true';
		$this->hasBlackList = $hasBlackList;
		if ($hasBlackList)
		{
			$this->getLogger()->info('Blacklist cache enabled.');
		} else {
			$this->getLogger()->info('Blacklist cache disabled.');
		}
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
	
}

?>