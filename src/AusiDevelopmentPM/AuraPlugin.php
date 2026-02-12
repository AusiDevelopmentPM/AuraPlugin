<?php

namespace AusiDevelopmentPM;

use AusiDevelopmentPM\command\AuraCommand;
use AusiDevelopmentPM\listener\EventListener;
use AusiDevelopmentPM\task\KnockbackTask;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class AuraPlugin extends PluginBase
{
    private static self $instance;
    private Permission $usePerm;
    private Permission $bypassPerm;
    private string $cmdName;
    private float|int $radius;
    private float|int $knockbackPower;
    private array $noFallDamage = [];

    public function onEnable(): void
    {
        self::$instance = $this;

        $this->saveDefaultConfig();
        $this->usePerm = new Permission($this->getConfig()->getNested("Aura.Command.Permissions.Use", "aura.use"));
        $this->bypassPerm = new Permission($this->getConfig()->getNested("Aura.Command.Permissions.Bypass", "aura.bypass"));
        $this->cmdName = (($got = $this->getConfig()->getNested("Aura.Command.Name", "epstein")));
        $this->radius = (($got = $this->getConfig()->getNested("Aura.Radius")) !== null ? (is_numeric($got) ? (($val = floatval($got)) > 0 ? $val : 1) : 1) : 1);
        $this->knockbackPower = (($got = $this->getConfig()->getNested("Aura.knockbackPower")) !== null ? (is_numeric($got) ? (($val = floatval($got)) > 0 ? $val : 1) : 1) : 1);

        DefaultPermissions::registerPermission($this->usePerm, [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);
        DefaultPermissions::registerPermission($this->bypassPerm, [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("aura", new AuraCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new KnockbackTask(), 1);
        $this->getLogger()->info("AuraPlugin activated");
    }

    public function parse(string $key, array $parameters = []): string
    {
        $got = $this->getConfig()->getNested($key, $key);
        foreach ($parameters as $key => $value) {
            $got = str_replace("%" . $key . "%", $value, $got);
            $got = str_replace("§", "&", $got);
        }
        return $got;
    }

    public function getUsePerm(): Permission
    {
        return $this->usePerm;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function getBypassPerm(): Permission
    {
        return $this->bypassPerm;
    }

    public function getRadius(): float|int
    {
        return $this->radius;
    }

    public function getKnockbackPower(): float|int
    {
        return $this->knockbackPower;
    }

    public function getNoFallDamage(): array
    {
        return $this->noFallDamage;
    }



    public function onDisable(): void
    {
    }
}
