<?php
namespace MobsCash;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
//use MobsCash\Commands\ConfigCommand;
use Base\Commands;
class Loader extends PluginBase implements Listener{
	public $money;
	
	
	public function onDisable(){
		 $this->getLogger()->info(TextFormat::RED."Плагин выключен!");
	         }
	public function onEnable(){
		 @mkdir($this->getDataFolder());
      $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
        "Mobs" => "Настройки при убийстве моба",
		  "mcash"  => 50,
        "Player" => "Настройки при убийстве игрока",
		  "enable" => false,
		  "pcash" => 100,		
                         ));
      $this->messages = new Config($this->getDataFolder()."messages.properties", Config::PROPERTIES, array(
		"Mob_Kill" => "§aВы убили моба и получили {MONEY} денег!",
		"Player_Kill" => "§aВы убили игрока §c{PLAYER} §aи получили {MONEY} денег!",
		"Hooks_success" => "§aУспешно подключено к плагину: §2",
		"Hooks_error" => "§4Плагин не может быть включён, так как не установлена совместимая экономика!",
		"Plugin_on" => "§9[MobsCash] §bПлагин запущен!",
		"Plugin_off" => "§9[MobsCash] §cПлагин выключен!",
	  ));
		 $this->registerCommands();
        
                             $this->getServer()->getPluginManager()->registerEvents($this, $this);
                                 $load = $this->getServer()->getPluginManager();
                                      if(!($this->money = $load->getPlugin("PocketMoney"))  && !($this->money = $load->getPlugin("EconomyAPI")) && !($this->money = $load->getPlugin("MassiveEconomy"))){
                                         $this->getLogger()->info($this->messages->get("Hooks_error"));
                                             $this->getLogger()->info(TextFormat::GOLD.". Пожалуйста, установите: EconomyAPI, PocketMoney, MassiveEconomy!");
                                                     } else {
                                                         $this->getLogger()->info($this->messages->get("Hooks_success") . $this->money->getName(). " " .
			                                                     $this->money->getDescription()->getVersion());
																 		 $this->getLogger()->info($this->messages->get("Plugin_on"));
                                                                     }
  }
  	public function onEntityDeath(EntityDeathEvent $event){
               $entity = $event->getEntity();
        	     $cause = $entity->getLastDamageCause();
			         if($cause instanceof EntityDamageByEntityEvent) {
			           	 $killer = $cause->getDamager()->getPlayer();
									     # CustomMessage
					                     $Kmessage = str_replace("{MONEY}", $this->config->get("mcash"), $this->messages->get("Mob_Kill"));
										 $killer->sendMessage("$Kmessage");
																				 									 										 
										     # Conclusion
					                         $this->grantMoney($killer->getName(), $this->config->get("mcash"));
					                             return true;
				                                 
			                                         }else{
				                                         return true;
			                                                 }
		                                                         
	}
  public function grantMoney($p,$money) {
     if(!$this->money) return false;
        switch($this->money->getName()){
            case "PocketMoney":
                 $this->money->grantMoney($p, $money);
                     break;
                         case "MassiveEconomy":
                             $this->money->payPlayer($p,$money);
                                 break;
                                     case "EconomyAPI":
                                         $this->money->addMoney($p, $money);
                                             break;												 
										
                                                     default:
                                                         return false;
                                                             }
                                                                 return true;
  }
	private function unregisterCommands(array $commands){
        $commandmap = $this->getServer()->getCommandMap();
        foreach($commands as $commandlabel){
            $command = $commandmap->getCommand($commandlabel);
            $command->setLabel($commandlabel . "_disabled");
            $command->unregister($commandmap);
        }
    }
    /**
     * Function to register all EssentialsPE's commands...
     * And to override some default ones
     */
    private function registerCommands(){
        //Unregister commands to override
        $this->unregisterCommands([
           "list"
        ]);
        //Register the new commands
        $this->getServer()->getCommandMap()->registerAll("EssentialsPE", [
           // new ConfigCommand($this),
		]);
	}
}