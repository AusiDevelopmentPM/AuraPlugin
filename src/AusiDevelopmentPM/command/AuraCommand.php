<?php

namespace AusiDevelopmentPM\command;

use AusiDevelopmentPM\AuraPlugin;
use AusiDevelopmentPM\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AuraCommand extends Command {

    public function __construct()
    {
        parent::__construct(AuraPlugin::getInstance()->parse("Aura.Command.Name"), AuraPlugin::getInstance()->parse("Aura.Command.Description"), "");
        $this->setPermission(AuraPlugin::getInstance()->getUsePerm()->getName());
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if ($sender->hasPermission(AuraPlugin::getInstance()->getUsePerm()->getName())) {
                $sender->sendForm(new MainForm($sender));
            } else {
                $sender->sendMessage(AuraPlugin::getInstance()->parse("Aura.Messages.NoPermission"));
            }
        }

        return true;
    }
}