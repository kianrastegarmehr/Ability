<?php

namespace kianrastegar\Ability;

use kianrastegar\Ability\AbilityTask;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\entity\Living;
use pocketmine\entity\EffectInstance;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Effect;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{

    private $cooldown = [];

    public function onEnable()
    {
        $this->getLogger()->info("Ability has been enabled!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($sender instanceof Player) {
            $player = $sender->getPlayer();

            if ($command->getName() === "ability") {
                if (count($args) === 0) {
                    $sender->sendMessage(TextFormat::YELLOW . "Available abilities:");
                    $sender->sendMessage(TextFormat::AQUA . "/ability fireresistance /ability fr - Fire Resistance (2 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability regeneration /ability rr - Regeneration (2 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability jumpboost /ability jb - Jump Boost (4 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability invisibility /ability ib - Invisibility (1 minute)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability nightvision /ability nv - Night Vision (8 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability resistance /ability rt - Resistance (2 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability speed /ability sd - Speed (4 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability strength /ability st - Strength (2 minutes)");
                    $sender->sendMessage(TextFormat::AQUA . "/ability waterbreathing /ability wb - Water Breathing (2 minutes)");
                    return true;
                }

                $ability = strtolower($args[0]);

                if (isset($this->cooldown[$player->getName()][$ability]) && time() - $this->cooldown[$player->getName()][$ability] < 60) {
                    $sender->sendMessage(TextFormat::RED . "You must wait 1 minute before using this ability again.");
                    return true;
                }

                $this->runAbility($player, $ability);
                $this->cooldown[$player->getName()][$ability] = time();
                return true;
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            return true;
        }

        return false;
    }

    // ...

    private function runAbility(Player $player, string $ability)
    {
        switch ($ability) {
            case "fireresistance":
            case "fr":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 2 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Fire Resistance for 2 minutes.");
                break;

            case "regeneration":
            case "rr":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 2 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Regeneration for 2 minutes.");
                break;

            case "jumpboost":
            case "jb":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 4 * 60 * 20, 2));
                $player->sendMessage(TextFormat::GREEN . "You now have Jump Boost for 4 minutes.");
                break;

            case "invisibility":
            case "ib":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 1 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You are now invisible for 1 minute.");
                break;

            case "nightvision":
            case "nv":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 8 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Night Vision for 8 minutes.");
                break;

            case "resistance":
            case "rt":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 2 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Resistance for 2 minutes.");
                break;

            case "speed":
            case "sd":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 4 * 60 * 20, 2));
                $player->sendMessage(TextFormat::GREEN . "You now have Speed for 4 minutes.");
                break;

            case "strength":
            case "st":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 2 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Strength for 2 minutes.");
                break;

            case "waterbreathing":
            case "wb":
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::WATER_BREATHING), 2 * 60 * 20));
                $player->sendMessage(TextFormat::GREEN . "You now have Water Breathing for 2 minutes.");
                break;

            default:
                $player->sendMessage(TextFormat::RED . "Invalid ability.");
                break;
        }
        $this->getScheduler()->scheduleDelayedTask(new AbilityTask($this, $player, $ability), 60 * 20);
    }
}
