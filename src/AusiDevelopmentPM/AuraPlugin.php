<?php

namespace AusiDevelopmentPM;

use pocketmine\plugin\PluginBase;

class AuraPlugin extends PluginBase {

    private static AuraPlugin $instance;

    public function onEnable(): void
    {
        self::$instance = $this;

        $this->getLogger()->info("AuraPlugin activated");
    }

    public static function getInstance(): AuraPlugin
    {
        return self::$instance;
    }

    public function onDisable(): void
    {

    }

}