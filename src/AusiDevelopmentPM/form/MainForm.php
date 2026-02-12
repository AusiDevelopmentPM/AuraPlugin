<?php

namespace AusiDevelopmentPM\form;

use AusiDevelopmentPM\api\API;
use AusiDevelopmentPM\AuraPlugin;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class MainForm extends MenuForm
{
    private array $options = [];

    public function __construct(Player $player)
    {
        if (API::hasAura($player)) {
            $this->options[] = new MenuOption(AuraPlugin::getInstance()->parse("Aura.Messages.UI.Button.Deactivate"));
        } else {
            $this->options[] = new MenuOption(AuraPlugin::getInstance()->parse("Aura.Messages.UI.Button.Activate"));
        }

        parent::__construct(
            AuraPlugin::getInstance()->parse("Aura.Messages.UI.Title"),
            AuraPlugin::getInstance()->parse("Aura.Messages.UI.Content", ["status" => (API::hasAura($player) ? AuraPlugin::getInstance()->parse("Aura.Messages.Status.Active") : AuraPlugin::getInstance()->parse("Aura.Messages.Status.Inactive"))]),
            $this->options,
            function (Player $player, int $data): void {
                if (API::hasAura($player)) {
                    API::deactivateAura($player);
                    $player->sendMessage(AuraPlugin::getInstance()->parse("Aura.Messages.Deactivated"));
                } else {
                    if ($player->hasPermission(AuraPlugin::getInstance()->getUsePerm()->getName())) {
                        API::activateAura($player);
                        $player->sendMessage(AuraPlugin::getInstance()->parse("Aura.Messages.Activated"));
                    } else {
                        $player->sendMessage(AuraPlugin::getInstance()->parse("Aura.Messages.NoPermission"));
                    }
                }
            }
        );
    }
}
