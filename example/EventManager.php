<?php

require(__DIR__ . '/../vendor/autoload.php');

$loop = \Gear\Loop\LoopFactory::create();

$loop->addTick(new \Gear\Loop\Tick\PcntlTick());
$loop->addTick(new \Gear\Loop\Tick\CallableTick('test', function () {
    echo 123123;
}, 2));

$loop->start();
