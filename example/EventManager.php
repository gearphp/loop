<?php

require(__DIR__ . '/../vendor/autoload.php');

//$loop = \Gear\Loop\LoopFactory::create();
$loop = new \Gear\Loop\Manager\WhileManager();

$loop->addTick(new \Gear\Loop\Tick\PcntlTick());
$loop->addTick(new \Gear\Loop\Tick\CallableTick('test', function () {
    echo 123123;
}, 1));

$loop->start();
