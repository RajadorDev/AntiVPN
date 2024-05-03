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

namespace AntiVPN\task;

use CurlHandle;

use AntiVPN\Manager;

use AntiVPN\utils\AntiVPNAPI;

use pocketmine\Server;

use pocketmine\player\Player;

use pocketmine\scheduler\AsyncTask;

class CheckTask extends AsyncTask 
{
	
	const FAIL_CURL_INIT = 0;
	
	const FAIL_CURL_EXECUTE = 1;
	
	const FAIL_CURL_JSON = 2;
	
	const FAIL_INVALID_RESPONSE = 3;
	
	const FAIL_API = 4;
	
	const SUCESS = 5;
	
	/** @var String **/
	protected String $ip, $username, $token;
	
	/**
	 * @param String $ip 
	 * @param String $user
	 * @param String $token
	 * @param Callable (String $username, String $ip, ? Player $player, bool $isSafe) : void
 	**/
	public function __construct(String $ip, String $user, String $token, callable $callback) 
	{
		$this->ip = $ip;
		$this->username = $user;
		$this->token = $token;
		$this->storeLocal('call', $callback);
	}
	
	public function onRun() : void
	{
		$url = AntiVPNAPI::makeUrl($this->ip, $this->token);
		$curl = curl_init($url);
		if ($curl instanceof CurlHandle)
		{
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, false);
			$response = curl_exec($curl);
			if (($errorCode = curl_errno($curl)) == 0)
			{
				if (is_string($response))
				{
					if (json_validate($response))
					{
						$data = json_decode($response, true);
						if (isset($data[AntiVPNAPI::CHECK_INDEX]))
						{
							$data = $data[AntiVPNAPI::CHECK_INDEX];
							$willBlock = $data['vpn'] || $data['proxy'];
							$this->setResult([self::SUCESS, !$willBlock]);
						} else if (isset($data[AntiVPNAPI::CHECK_ERROR_INDEX])) {
							$this->setResult([self::FAIL_API, $data[AntiVPNAPI::CHECK_ERROR_INDEX]]);
						} else {
							$this->setResult([self::FAIL_API, 'Unknow error!']);
						}
					} else {
						$this->setResult([self::FAIL_CURL_JSON, 'Invalid json: ' . $response]);
					}
				} else {
					$this->setResult([self::FAIL_INVALID_RESPONSE, 'Invalid response type: ' . gettype($response)]);
				}
			} else {
				$this->setResult([self::FAIL_CURL_EXECUTE, curl_strerror($errorCode)]);
			}
			curl_close($curl);
		} else {
			$this->setResult([self::FAIL_CURL_INIT]);
		}
	}
	
	public function onCompletion(Server $server) 
	{
		$result = $this->getResult();
		if (is_array($result) && isset($result[0]))
		{
			if ($result[0] == self::SUCESS)
			{
				$player = $server->getPlayerExact($this->username);
				$call = $this->fetchLocal('call');
				if (is_callable($call))
				{
					$call($this->username, $this->ip, $player, $result[1]);
				}
			} else {
				switch ($errorCode = $result[0])
				{
					case self::FAIL_CURL_INIT:
						$errorName = 'Fail to open curl';
					break;
					case self::FAIL_CURL_EXECUTE:
						$errorName = 'Fail to execute curl';
					break;
					case self::FAIL_INVALID_RESPONSE:
						$errorName = 'Fail to process response';
					break;
					case self::FAIL_CURL_JSON:
						$errorName = 'Fail to load json response';
					break;
					case self::FAIL_API:
						$errorName = 'Fail api';
					break;
					default:
						$errorName = 'Unknow error code: ' . $errorCode;
					break;
				}
				$message = isset($result[1]) ? $result[1] : 'Unknow error message';
				Manager::getInstance()->getLogger()->error($errorName . ': ' . $message);
			}
		}
	}
	
}

?>