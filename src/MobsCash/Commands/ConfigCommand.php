<?php
namespace MobsCash\Commands;
use MobsCash\Loader;
use Base\Commands;
use pocketmine\utils\config;
use pocketmine\command\CommandSender;
class ConfigCommand extends Commands{
	public function __construct(Loader $plugin){
        parent::__construct($plugin, "mobscashconfig", "Изменение конфигураций", "/mobscashconfig <Ключ> <Значение>", null, ["mcc"]);
        $this->setPermission("mobscash.admin.config");
    }

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
   $value = $args[0];
	   if(is_numeric($args[0])){
   $this->getPlugin()->config();
	   }else{
		   $sender->sendMessage("§cЗначение конфигурации должно состоять из цифр!");
	   }
   } 
}