<?php

namespace kianrastegar\Ability;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class AbilityTask extends Task
{

    private $plugin;
    private $player;
    private $ability;

    public function __construct(Main $plugin, Player $player, string $ability)
    {
        $this->plugin = $plugin;
        $this->player = $player;
        $this->ability = $ability;
    }

    public function onRun(int $currentTick)
    {
        $playerName = $this->player->getName();
        $ability = strtolower($this->ability);

        if (isset($this->plugin->cooldown[$playerName][$ability])) {
            unset($this->plugin->cooldown[$playerName][$ability]);
            $this->player->sendMessage(TextFormat::GREEN . "The cooldown for $ability has expired.");
        }
    }
}
