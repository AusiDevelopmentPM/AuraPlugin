<?php

namespace AusiDevelopmentPM\task;

use AusiDevelopmentPM\api\API;
use AusiDevelopmentPM\AuraPlugin;
use pocketmine\entity\Living;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KnockbackTask extends Task
{
    public function onRun(): void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (API::hasAura($player)) {
                if (!$player->hasPermission(AuraPlugin::getInstance()->getUsePerm()->getName())) {
                    API::deactivateAura($player);
                    continue;
                }

                foreach ($player->getWorld()->getEntities() as $entity) {
                    if ($entity instanceof Player) {
                        if ($entity->getName() == $player->getName()) {
                            continue;
                        }
                        if ($entity->isSpectator()) {
                            continue;
                        }
                        if (!$entity->hasPermission(AuraPlugin::getInstance()->getBypassPerm()->getName())) {
                            if ($player->getPosition()->distance($entity->getPosition()) <= AuraPlugin::getInstance()->getRadius()) {
                                $entity->knockBack(
                                    $entity->getPosition()->getX() - $player->getPosition()->getX(),
                                    $entity->getPosition()->getZ() - $player->getPosition()->getZ(),
                                    AuraPlugin::getInstance()->getKnockbackPower(),
                                    0.7
                                );

                                if ($entity->isSurvival(true) || $entity->isAdventure(true)) {
                                    AuraPlugin::getInstance()->getNoFallDamage()[$entity->getName()] = $entity->getName();
                                }
                            }
                        }
                    } elseif ($entity instanceof Living) {
                        if ($player->getPosition()->distance($entity->getPosition()) <= AuraPlugin::getInstance()->getRadius()) {
                            $entity->knockBack(
                                $entity->getPosition()->getX() - $player->getPosition()->getX(),
                                $entity->getPosition()->getZ() - $player->getPosition()->getZ(),
                                AuraPlugin::getInstance()->getKnockbackPower(),
                                0.7
                            );
                        }
                    }
                }
            }
        }
    }
}
