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

use AntiVPN\utils\AntiVPNAPI;

use AntiVPN\event\StartCheckEvent;

use AntiVPN\command\AntiVPNCommand;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\player\Player;

final class Manager extends PluginBase 
{
	
	/** @var Config **/
	private Config $whiteList;
	
	/** @var Config | null **/
	private ? Config $cache = null;
	
	/** @var Manager **/
	private Manager $instance;
	
	/** @var bool **/
	private bool $hasCacheEnabled = true;
	
	/** @var String | null **/
	private ? String $key = null;
	
	/** @var String[] **/
	private array $inProcess = array();
	
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
		$this->getLogger()->info('Loading key...');
		$this->loadKey();
	}
	
	public function onDisable() : void 
	{
		if ($this->isCacheEnabled())
		{
			$this->getCacheList()->save();
		}
		if ($this->whiteList instanceof Config) 
		{
			$this->whiteList->save();
		}
	}
	
	private function initResource() : void 
	{
		$this->saveResource('config.yml', false);
		$this->saveResource('key.txt', false);
	}
	
	private function loadPreferences() : void 
	{
		$hasCacheEnabled = $this->getConfigValue('enable-cache') == 'true';
		$this->hasCacheEnabled = $hasCacheEnabled;
		if ($hasCacheEnabled)
		{
			$dir = $this->getDataFolder() . 'cache.json';
			$this->cache = new Config($dir, Config::JSON);
			$this->getLogger()->info('Cache enabled.');
		} else {
			$this->getLogger()->info('Cache disabled.');
		}
	}
	
	private function initCommand() : void 
	{
		$this->getServer()->getCommandMap()->register('antivpn', new AntiVPNCommand());
	}
	
	private function loadKey() : void 
	{
		$key = file_get_contents($this->getDataFolder() . 'key.txt');
		if (AntiVPNAPI::isValidKey($key))
		{
			$this->key = $key;
			$this->getLogger()->info('Key loaded suceffully.');
		} else {
			$this->getLogger()->warning('Key: "' . $key . '" is not a valid key! Please set your antivpn.com key using: /antivpn setkey <your_key>');
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
	
	public function isCacheEnabled() : bool 
	{
		return $this->hasCacheEnabled;
	}
	
	public function getCacheList() : ? Config 
	{
		return $this->cache;
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
	
	public function addWhitelisted(String | Player $player) : void 
	{
		if ($player instanceof Player)
		{
			$player = $player->getName();
		}
		$this->whiteList->set(strtolower($player));
	}
	
	public function inCache(Player $player) : bool 
	{
		$address = $player->getNetworkSession()->getIp();
		return $this->getCacheList()->exists($address);
	}
	
	public function getCacheValue(Player $player) : bool 
	{
		if ($this->inCache($player))
		{
			$adr = $player->getNetworkSession()->getIp();
			return $this->getCacheList()->get($adr, false) == 'true';
		}
		return false;
	}
	
	public function addCachedValue(String $ip, bool $result) : void 
	{
		$this->getCacheList()->set($ip, $result);
	}
	
	public function hasKey() : bool 
	{
		return is_string($this->key) && AntiVPNAPI::isValidKey($this->key);
	}
	
	public function setKey(String $newKey) : void 
	{
		$this->key = $newKey;
	}
	
	public function saveKey() : void 
	{
		if (!empty($this->key))
		{
			file_put_contents($this->getDataFolder() . 'key.txt', $this->key);
		}
	}
	
	public function getKickScreenMessage(String $playerName) : String 
	{
		$message = (string) $this->getConfigValue('kick-screen-message', '§cYou can\'t use vpn here!');
		return str_replace('{player}', $playerName, $message);
	}
	
	public function getAdminAlertMessage(String $playerName) : String 
	{
		$message = (string) $this->getConfigValue('alert-admin-message', '');
		return str_replace('{player}', $playerName, $message);
	}
	
	public function addInProcess(String $ip) : void 
	{
		if (!in_array($ip, $this->inProcess))
		{
			$this->inProcess[] = $ip;
		}
	}
	
	public function inProcess(String $ip) : bool 
	{
		return in_array($ip, $this->inProcess);
	}
	
	public function removeFromProcess(String $ip) : void 
	{
		if (in_array($ip, $this->inProcess))
		{
			$id = array_search($ip, $this->inProcess);
			unset($this->inProcess[$id]);
		}
	}
	
	public function startCheck(Player $player, callable $call) : bool
	{
		
		if (!$this->hasKey())
		{
			$this->getLogger()->warning('Trying to check address, but theres no api key setted!');
			return false;
		} else if ($this->inProcess($ip = $player->getNetworkSession()->getIp())) {
			$this->getLogger()->debug('tring to check ip ' . $ip . ' but this ip already is checking by the system.');
			return false;
		}
		
		$ev = new StartCheckEvent($player);
		$ev->call();
		if (!$ev->isCancelled())
		{
			$this->getScheduler()->scheduleAsyncTask(new CheckTask($player->getNetworkSession()->getIp(), strtolower($player->getName()), $this->getKey(), $call));
			return true;
		}
		return false;
	}
	
}

?>