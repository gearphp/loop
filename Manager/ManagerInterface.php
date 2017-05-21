<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\TickInterface;

interface ManagerInterface
{
    /**
     * Add tick to loop.
     *
     * @param TickInterface $tick
     * @return $this
     */
    public function addTick(TickInterface $tick);

    /**
     * Remove tick by name from loop.
     *
     * @param string $name
     * @return void
     */
    public function removeTick($name);

    /**
     * Start loop.
     *
     * @return void
     */
    public function start();

    /**
     * Stop loop.
     *
     * @return void
     */
    public function stop();

    /**
     * Is loop start.
     *
     * @return boolean
     */
    public function isStart();

    /**
     * On start loop.
     *
     * @param callable $callable
     * @return $this
     */
    public function onStart($callable);

    /**
     * On stop loop.
     *
     * @param callable $callable
     * @return $this
     */
    public function onStop($callable);

    /**
     * On exception.
     *
     * @param callable $callable
     * @return $this
     */
    public function onException($callable);

    /**
     * Get start at timestamp.
     *
     * @return integer|null
     */
    public function startAt();

    /**
     * Get stop at timestamp.
     *
     * @return integer|null
     */
    public function stopAt();
}