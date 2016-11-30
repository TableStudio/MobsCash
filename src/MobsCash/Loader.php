<?php
namespace MobsCash;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\Player;
use pocketmine\Server;
class Loader extends PluginBase implements Listener{
	public $money;
	
	
	public function onDisable(){
		 $this->getLogger()->info(TextFormat::RED."Плагин выключен!");
	         }
	public function onEnable(){
		 @mkdir($this->getDataFolder());
      $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
        "Cash"  => 50,
            "message" => "§aВы убили моба и получили {Money} д.е.",
                         ));
        
                             $this->getServer()->getPluginManager()->registerEvents($this, $this);
                                 $load = $this->getServer()->getPluginManager();
                                      if(!($this->money = $load->getPlugin("PocketMoney"))  && !($this->money = $load->getPlugin("EconomyAPI")) && !($this->money = $load->getPlugin("MassiveEconomy"))){
                                         $this->getLogger()->info(TextFormat::GOLD." §4Плагин неможет быть включен, так как нет совместимой экономики: EconomyAPI, EconomyMaster, PocketMoney и MassiveEconomy");
                                             $this->getLogger()->info(TextFormat::GOLD.". Пожалуйста, установите их!");
                                                 $this->getLogger()->info(TextFormat::GOLD."§4Error: null");
                                                     } else {
                                                         $this->getLogger()->info(TextFormat::GREEN."Подключение к  ".
			                                                 TextFormat::GREEN.$this->money->getName()." ".
			                                                     $this->money->getDescription()->getVersion());
																 		 $this->getLogger()->info(TextFormat::GREEN."Плагин успешно включён!");
                                                                     }
  }
  	public function onEntityDeath(EntityDeathEvent $event){
               $entity = $event->getEntity();
        	     $cause = $entity->getLastDamageCause();
			         if($cause instanceof EntityDamageByEntityEvent) {
			           	 $killer = $cause->getDamager()->getPlayer();
									     # CustomMessage
					                     $Kmessage = str_replace("{Money}", $this->config->get("Cash"), $this->config->get("message"));
										 $killer->sendMessage("$Kmessage");
																				 									 										 
										     # Conclusion
					                         $this->grantMoney($killer->getName(), $this->config->get("Cash"));
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
}