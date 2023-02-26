<?php

namespace RajadorAntiVPN;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use RajadorAntiVPN\Listener\Events;
use RajadorAntiVPN\Tasks\CheckVPNTask;

/*
* Created by Rajador Developer
*
* Discord: Rajador#7070
*
* YouTube: https://youtube.com/channel/UC1UJFxth-YRkNuLBqBYyqbA
*/

class System extends PluginBase {
	
	const IS_DEBUG = false;
	/* Instance */
	public static $system;
	
	private $pluginConfig = [];
	
	private $allowedCache = [];
	
	private $blockedCache = [];
	
	public $defaultConfig = [
	'kick-msg' => '§cYou cannot use §6VPN §chere',
	'adm-msg' => '§cThe player §f{player} §cis using VPN and was kicked out',
	'adm-warn' => 'true',
	'adm-permission' => 'vpn.warn'
	];
	
	private $checking = [];
	
	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
		$this->getLogger()->info('§eOpening folders....');
		$this->initFolders();
		$this->getLogger()->info('§eLoading config....');
		$this->loadConfig();
		$this->getLogger()->info("\n \n §eAnti VPN Enabled\n\n§bCreated By: §7Rajador\n\n§9Discord: §fRajador#7070\n\n§fYou§cTube: §fhttps://youtube.com/channel/UC1UJFxth-YRkNuLBqBYyqbA\n\n");
		self::$system = $this;
	}
	
	public function getConfigData() : array 
	{
		return $this->pluginConfig;
	}
	
	public function initFolders()
	{
		@mkdir($this->getDataFolder());
	}
	
	
	public function loadConfig()
	{
		$this->pluginConfig = (new Config($this->getDataFolder().'config.yml', Config::YAML, $this->defaultConfig))->getAll();
		
	}
	
	public function isBlocked(Player $p) : bool 
	{
		return isset($this->blockedCache[$p->getAddress()]);
	}
	
	public function isAllowed(Player $p) : bool 
	{
		return isset($this->allowedCache[$p->getAddress()]);
	}
	
	public static function getInstance()
	{
		return self::$system;
	}
	
	public function startCheckVPN(Player $p)
	{
		if($this->isChecking($p))
		return false;
		
		$this->checking[$p->getAddress()] = true;
		$this->getServer()->getScheduler()->scheduleAsyncTask(new CheckVPNTask($p->getName(), $p->getAddress()));
	}
	
	public function finishCheckVPN(String $name, String $ip, bool $isVpn)
	{
		unset($this->checking[$ip]);
		if($isVpn)
		$this->blockAddress($ip, $name);
		else
		$this->allowedCache[$ip] = $name;
		
	}
	
	public function isChecking(Player $p) : bool 
	{
		return isset($this->checking[$p->getAddress()]);
	}
	
	public function blockAddress(String $ip, String $name)
	{
		foreach($this->getServer()->getOnlinePlayers() as $every)
		{
			if($every->getAddress() == $ip)
			{
				$every->close('', $this->pluginConfig['kick-msg']);
			}
		}
		$this->blockedCache[$ip] = $name;
		if($this->pluginConfig['adm-warn'])
		foreach($this->getServer()->getOnlinePlayers() as $every)
		if($every->hasPermission($this->pluginConfig['adm-permission']))
		$every->sendMessage(str_ireplace('{player}', $name, $this->pluginConfig['adm-msg']));
	} 
	
}
