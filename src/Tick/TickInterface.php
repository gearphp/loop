<?php

namespace Gear\Loop\Tick;

use Gear\Loop\Manager\ManagerInterface;

interface TickInterface
{
    /**
     * Tick.
     */
    public function tick(): void;

    /**
     * Get tick name.
     */
    public function getName(): string;

    /**
     * Set manager.
     */
    public function setManager(?ManagerInterface $manager): self;

    /**
     * Get manager.
     */
    public function getManager(): ?ManagerInterface;

    /**
     * Get tick interval time.
     */
    public function getInterval(): int;

    /**
     * Set tick interval time.
     */
    public function setInterval(int $interval): self;

    /**
     * On exception.
     */
    public function onException(callable $callable): self;
}
