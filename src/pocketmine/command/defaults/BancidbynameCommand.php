<?php
namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;

class BancidbynameCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.bancidbyname.description",
			"%commands.bancidbyname.usage",
			["/"]
		);
		$this->setPermission("pocketmine.command.bancidbyname");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$name = array_shift($args);
		$reason = implode(" ", $args);
		
		if ($sender->getServer()->getPlayer($name) instanceof Player) $target = $sender->getServer()->getPlayer($name);
		else return false;

		$sender->getServer()->getCIDBans()->addBan($target->getClientId(), $reason, null, $sender->getName());
		$sender->getServer()->getNameBans()->addBan($target->getName(), $reason = implode(" ", $args), null, $sender->getName());
		$n = $target->getName();
		$target->kick($reason !== '' ? 'Banned by admin. Reason:'.$reason : 'Banned by admin.');
		$sender->getServer()->broadcastMessage('§l§b[§6Orange§aCraft§b] §eBanned §a'.$target->getName().' §eforever for §cHack§f! §aby§e '.$sender->getName());
		return true;
	}
}
