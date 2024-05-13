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

namespace AntiVPN\command;

use AntiVPN\Manager;

use AntiVPN\utils\AntiVPNAPI;

use AntiVPN\form\{WhiteListMainForm, ConfirmAddWhiteListForm, RemovePlayerForm, AddPlayerForm};

use pocketmine\Server;

use pocketmine\player\Player;

use pocketmine\command\{Command, CommandSender};

use pocketmine\plugin\{PluginOwned, PluginOwnedTrait, Plugin};

final class AntiVPNCommand extends Command implements PluginOwned
{
	
	use PluginOwnedTrait {
		__construct as setOwner;
		getOwningPlugin as getManager;
	}
	
	const COMMAND_PREFIX = '§6Anti§cVPN§r  ';
	
	/** @var String **/
	private String $wlUsage = "§8---====(§6Anti§cVPN§f WhiteList§8)====---\n§8>  §f/{command_label} {argument_label} add <player_name> §7To add whitelisted player.\n§8>  §f/{command_label} {argument_label} remove <player_name>§7 To remove whitelisted player.\n§8>  §f/{command_label} {argument_label} list §7To see the whitelist.";
	
	public function __construct() 
	{
		$this->setOwner(Manager::getInstance());
		parent::__construct 
		(
			'antivpn',
			'AntiVPN manager command',
			"§8---====(§6Anti§cVPN§8)====---\n§8>  §f/{command_label} setkey <key>§7 To set api key\n§8>  §f/{command_label} wl §7To manage AntiVPN whitelist.",
			['avpn', 'antvpn', 'antiproxy']
		);
		$this->setPermission('antivpn.command');
	}
	
	public function execute(CommandSender $p, String $label, array $args) 
	{
		if ($this->testPermission($p))
		{
			if (isset($args[0]) && trim($args[0]) != '')
			{
				switch (strtolower($args[0]))
				{
					case 'key':
					case 'secret':
					case 'setkey':
					case 'setsecret':
						if ($this->testPermission($p, 'antivpn.command.secret'))
						{
							if (isset($args[1]) && trim($args[1]) != '')
							{
								$key = $args[1];
								if (isset($args[2]))
								{
									$key = $args;
									unset($key[0]);
									$key = implode(' ', $key);
								}
								
								if (AntiVPNAPI::isValidKey($key))
								{
									$this->getOwningPlugin()->setKey($key);
									$this->getOwningPlugin()->saveKey();
									$p->sendMessage(self::COMMAND_PREFIX . '§7Key setted §l§aSuceffully§r§7.');
								} else {
									$p->sendMessage(self::COMMAND_PREFIX . "§7\"§f{$key}§7\" §cis not a valid key!");
								}
								
							} else {
								$p->sendMessage(self::COMMAND_PREFIX . "§7Use: §f/{$label} {$args[0]} <key>");
							}
						}
					break;
					case 'wl':
					case 'white-list':
					case 'whitelist':
						if ($this->testPermission($p, 'antivpn.command.whitelist'))
						{
							if (isset($args[1]) && trim($args[1]) != '')
							{
								switch (strtolower($args[1]))
								{
									case 'add':
									case 'set':
									case 'new':
										if (isset($args[2]) && trim($args[2]) != '')
										{
											$target = Server::getInstance()->getPlayerExact($name = $args[2]);
											if ($target instanceof Player) 
											{
												$name = $target->getName();
											}
											
											if ($p instanceof Player) 
											{
												(new ConfirmAddWhiteListForm($name))->sendToPlayer($p);
											} else {
												$this->getOwningPlugin()->addWhitelisted($name);
												$p->sendMessage(self::COMMAND_PREFIX . '§7Player §f' . $name . ' §7added to whitelist §a§lSuceffully§r§7.');
											}
											
										} else if ($p instanceof Player) {
											(new AddPlayerForm($p))->sendToPlayer($p);
										} else {
											$p->sendMessage(self::COMMAND_PREFIX . "§7To add whitelisted player use: §f/{$label} {$args[0]} {$args[1]} <player_name>");
										}
									break;
									case 'remove':
									case 'delete':
									case 'unset':
										if (isset($args[2]) && trim($args[2]) != '')
										{
											$user = $args[2];
											if ($this->getOwningPlugin()->isWhitelisted($user))
											{
												$user = strtolower($user);
												$this->getOwningPlugin()->getWhiteList()->remove($user);
												$p->sendMessage("§7User {$args[2]} §7removed §a§lSuceffully§r§7.");
											} else {
												$p->sendMessage(self::COMMAND_PREFIX . "§7Player §f{$user} §7is not whitelisted!");
											}
										} else if ($p instanceof Player) {
											if (count($this->getOwningPlugin()->getWhiteList()->getAll()) > 0)
											{
												(new RemovePlayerForm)->sendToPlayer($p);
											} else {
												$p->sendMessage(self::COMMAND_PREFIX . '§7Theres no players added in WhiteList.');
											}
										} else {
											$p->sendMessage(self::COMMAND_PREFIX . "§7To remove player use: §f/{$label} {$args[0]} {$args[1]} <player_name>");
										}
									break;
									case 'list':
									case 'all':
										Manager::sendWhiteList($p);
									break;
									default:
										if ($p instanceof Player) 
										{
											(new WhiteListMainForm())->sendToPlayer($p);
										} else {
											$this->showWhitelistUsageTo($p, $label, $args[0]);
										}
									break;
								}
							} else if ($p instanceof Player) {
								(new WhiteListMainForm())->sendToPlayer($p);
							} else {
								$this->showWhitelistUsageTo($p, $label, $args[0]);
							}
						}
					break;
					default:
						$this->showUsageTo($p, $label);
					break;
				}
			} else {
				$this->showUsageTo($p, $label);
			}
		}
	}
	
	public function showUsageTo(CommandSender $p, String $label) : void 
	{
		$p->sendMessage(str_replace('{command_label}', $label, $this->getUsage()));
	}
	
	public function showWhitelistUsageTo(CommandSender $player, String $commandLabel, String $whitelistLabel = 'wl') : void 
	{
		$usage = str_replace
		(
			['{command_label}', '{argument_label}'],
			[$commandLabel, $whitelistLabel],
			$this->wlUsage
		);
		$player->sendMessage($usage);
	}
	
	/**
	 * @return Manager
 	**/
	public function getOwningPlugin() : Plugin 
	{
		return $this->getManager();
	}
	
}
