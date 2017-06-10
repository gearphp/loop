<?php

namespace Gear\Loop\Tick;

use Gear\Loop\Manager\ManagerInterface;

/**
 * Abstract tick.
 */
abstract class AbstractTick implements TickInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $interval;

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var [callable]
     */
    protected $onException = [];

    /**
     * AbstractTick constructor.
     *
     * @param $name
     * @param int $interval
     */
    public function __construct($name, $interval = 0)
    {
        $this->name = $name;
        $this->setInterval($interval);
    }

    /**
     * Get tick name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set manager.
     *
     * @param ManagerInterface $manager
     * @return $this
     */
    public function setManager(ManagerInterface $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return ManagerInterface|null
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Get tick interval time.
     *
     * @return integer
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set tick interval time.
     *
     * @param $interval
     * @return $this
     */
    public function setInterval($interval)
    {
        $this->interval = (integer)$interval >= 0 ? (integer)$interval : 0;

        return $this;
    }

    /**
     * On exception.
     *
     * @param callable $callable
     * @return $this
     */
    public function onException($callable)
    {
        $this->onException[] = $callable;

        return $this;
    }

    /**
     * Catch tick exception and throw it.
     *
     * @param \Exception $e
     * @throws \Exception
     */
    protected function catchException(\Exception $e)
    {
        foreach ($this->onException as $call) {
            call_user_func_array($call, [$e]);
        }

        throw $e;
    }
}