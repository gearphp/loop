<?php

namespace Gear\Loop;

use Gear\Loop\Manager\EventManager;
use Gear\Loop\Manager\ManagerInterface;
use Gear\Loop\Manager\WhileManager;

class Factory
{
    public static function create(): ManagerInterface
    {
        if (\class_exists('EventBase', false)) {
            return new EventManager();
        }

        return new WhileManager();
    }
}
