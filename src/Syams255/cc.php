<?php

namespace Syams255;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class cc extends PluginBase implements Listener {

    public $configFile;
    public $curse;
    public $advert;
    public $cursewords;
    public $advertwords;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->configFile = new Config($this->getDataFolder()."config.yml", Config::YAML, [
            "kick" => "true",
            "curseMessage" => "Â§4Â§lDon't curse on this server!",
            "advertMessage" => "Â§4Â§lDon't advertise on this server!"
        ]);
        $this->curse = new Config($this->getDataFolder()."curse.yml", Config::YAML, [
            "Words" => ""
        ]);
        $this->advert = new Config($this->getDataFolder()."advert.yml", Config::YAML, [
            "Words" => ""
        ]);

        $this->cursewords = array("fuck","fu","ck","fuc","uck","fu*k","f*ck","shit","poop","stupid","dumb","noob","anal","anus","arse","ass","ballsack","bastard","bitch","biatch","blowjob","blow job","boner","boob","butt","buttplug","cock","crap","cunt","dick","dildo"," dyke","Goddamn","God damn","homo","jerk","nigger","nigga","omg","penis","piss","pussy","queer","scrotum","sex","s hit","sh1t","slut","vagina","wank","whore","::");
        $this->advertwords = array(".leet", ".com", ".ru", ".io", ".tk", ".ga", ".cz", ".co", ".co.uk", ".me", ".ml", ".cf", ".us", ".info", ".org", "leet.",".cc","a1","a2","a3","a4","a5","a6","a7","a8","a9","b1","b2","b3","b4","b5","b6","b7","b8","b9","c1","c2","c3","c4","c5","c6","c7","c8","c9","192");

        $this->getLogger()->notice(TF::GREEN."Enabled!");
    }

    public function getwords() {
        $this->cursewords = $this->curse->get("Words");
        $this->advertwords = $this->advert->get("Words");
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        if(strtolower($command) === "cc" and $args[0] === "set") {
            if($sender->hasPermission("cc.set")) {
                $this->cursewords += $args[1];
            }
        }
    }

    public function onPlayerChat(PlayerChatEvent $event) {
        $msg = $event->getMessage();
        $player = $event->getPlayer();

        foreach($this->cursewords as $word) {
            if(strpos($msg, $word) !== false && !($player->hasPermission("block.bypass"))) {
                $event->setCancelled();
                if($this->configFile->get("kick") === "true") {
                    $event->getPlayer()->kick($this->configFile->get("curseMessage"), false);
                }
            }
        }

        foreach($this->advertwords as $word) {
            if(strpos($msg, $word) !== false && !($player->hasPermission("block.bypass"))) {
                $event->setCancelled();
                if($this->configFile->get("kick") === "true") {
                    $event->getPlayer()->kick($this->configFile->get("advertMessage"), false);
                }
            }
        }
    }
    public function onDisable() {
        $this->configFile->save();
        $this->curse->save();
        $this->advert->save();
        $this->getLogger()->notice(TF::GREEN."Disabled!");
    }
}
?>
