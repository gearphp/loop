<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\TickInterface;

interface ManagerInterface
{
    /**
     * Add tick to loop.
     */
    public function addTick(TickInterface $tick): self;

    /**
     * Remove tick by name from loop.
     */
    public function removeTick(string $name): self;

    /**
     * Start loop.
     */
    public function start(): void;

    /**
     * Stop loop.
     */
    public function stop(): void;

    /**
     * Is loop start.
     */
    public function isStart(): bool;

    /**
     * On start loop.
     */
    public function onStart(callable $callable): self;

    /**
     * On stop loop.
     */
    public function onStop(callable $callable): self;

    /**
     * On exception.
     */
    public function onException(callable $callable): self;

    /**
     * Set min tick interval.
     */
    public function setInterval(int $interval): self;

    /**
     * Get min tick interval.
     */
    public function getInterval(): int;
}
