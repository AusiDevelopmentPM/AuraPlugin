<?php

namespace AusiDevelopmentPM\listener;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use AusiDevelopmentPM\AuraPlugin;

class EventListener implements Listener
{
    public function onDamage(EntityDamageEvent $ev): void
    {
        $entity = $ev->getEntity();

        if ($entity instanceof Player) {
            if ($ev->getCause() == $ev::CAUSE_FALL) {
                if (isset(AuraPlugin::getInstance()->getNoFallDamage()[$entity->getName()])) {
                    unset(AuraPlugin::getInstance()->getNoFallDamage()[$entity->getName()]);
                    $ev->cancel();
                }
            }
        }
    }

    public function onCheck(PlayerGameModeChangeEvent $e): void
    {
        $p = $e->getPlayer();
        $old = $p->getGamemode();
        $new = $e->getNewGamemode();

        if ($old === GameMode::SURVIVAL() || $old === GameMode::ADVENTURE()) {
            if ($new === GameMode::CREATIVE() || $new === GameMode::CREATIVE()) {
                if (isset(AuraPlugin::getInstance()->getNoFallDamage()[$p->getName()])) {
                    unset(AuraPlugin::getInstance()->getNoFallDamage()[$p->getName()]);
                }
            }
        }
    }

    public function onQuit(PlayerQuitEvent $e): void
    {
        $p = $e->getPlayer();

        if (isset(AuraPlugin::getInstance()->getNoFallDamage()[$p->getName()])) {
            unset(AuraPlugin::getInstance()->getNoFallDamage()[$p->getName()]);
        }
    }
}
