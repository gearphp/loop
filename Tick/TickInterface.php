<?php

namespace Gear\Loop\Tick;

use Gear\Loop\Manager\ManagerInterface;

/**
 * Tick interface.
 */
interface TickInterface
{
    /**
     * Tick.
     *
     * @return void
     */
    public function tick();

    /**
     * Get tick name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set manager.
     *
     * @param ManagerInterface|null $manager
     * @return $this
     */
    public function setManager(ManagerInterface $manager = null);

    /**
     * Get manager.
     *
     * @return ManagerInterface|null
     */
    public function getManager();

    /**
     * Get tick interval time.
     *
     * @return integer
     */
    public function getInterval();

    /**
     * Set tick interval time.
     *
     * @param $interval
     * @return $this
     */
    public function setInterval($interval);

    /**
     * On exception.
     *
     * @param callable $callable
     * @return $this
     */
    public function onException($callable);
}