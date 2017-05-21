<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\Exceptions\TickException;
use Gear\Loop\Tick\TickInterface;

class WhileManager implements ManagerInterface
{
    /**
     * Min tick interval (msec).
     *
     * @var int
     */
    protected $interval = 100;

    /**
     * Ticks interface.
     *
     * @var TickInterface[]
     */
    protected $ticks = [];

    /**
     * Is loop start.
     *
     * @var bool
     */
    protected $isStart = false;

    /**
     * On start event.
     *
     * @var callable[]
     */
    protected $onStart = [];

    /**
     * On stop event.
     *
     * @var callable[]
     */
    protected $onStop = [];

    /**
     * On exception event.
     *
     * @var callable[]
     */
    protected $onException = [];

    /**
     * @var integer|null
     */
    protected $startAt;

    /**
     * @var integer|null
     */
    protected $stopAt;

    /**
     * Add tick to loop.
     *
     * @param TickInterface $tick
     * @return $this
     */
    public function addTick(TickInterface $tick)
    {
        $tick->setManager($this);
        $this->ticks[$tick->getName()] = [
            'tick' => $tick,
            'time' => 0,
        ];

        return $this;
    }

    /**
     * Remove tick by name from loop.
     *
     * @param string $name
     * @return void
     */
    public function removeTick($name)
    {
        if (isset($this->ticks[$name])) {
            $this->ticks[$name]->setManager(null);
            unset($this->ticks[$name]);
        }
    }

    /**
     * Start loop.
     *
     * @return void
     */
    public function start()
    {
        if ($this->isStart) {
            return;
        }

        $this->startAt = time();
        $this->stopAt  = null;
        $this->isStart = true;

        if (extension_loaded('xdebug')) {
            xdebug_disable();
        }

        foreach ($this->onStart as $callable) {
            call_user_func_array($callable, []);
        }

        while ($this->isStart) {
            $this->tick();
        }

        $this->stopAt = time();

        foreach ($this->onStop as $callable) {
            call_user_func_array($callable, []);
        }

        if (extension_loaded('xdebug')) {
            xdebug_enable();
        }
    }

    /**
     * Loop tick.
     */
    protected function tick()
    {
        $time = microtime(true);
        foreach ($this->ticks as $name => $value) {

            /* @var TickInterface $tick */
            $tick     = $value['tick'];
            $interval = $tick->getInterval() >= $this->interval ? $tick->getInterval() : $this->interval;
            $diff     = ($time - $value['time']) * 100;

            if ($diff >= $interval) {

                try {
                    $tick->tick();
                } catch (\Exception $e) {
                    $this->catchTickException($tick, $e);
                }

                $this->ticks[$name]['time'] = $time;
            }

        }

        usleep($this->interval * 1e3);
    }

    /**
     * Stop loop.
     *
     * @return void
     */
    public function stop()
    {
        $this->isStart = false;
    }

    /**
     * Is loop start.
     *
     * @return boolean
     */
    public function isStart()
    {
        return $this->isStart;
    }

    /**
     * On start loop.
     *
     * @param callable $callable
     * @return $this
     */
    public function onStart($callable)
    {
        $this->onStart[] = $callable;

        return $this;
    }

    /**
     * On stop loop.
     *
     * @param callable $callable
     * @return $this
     */
    public function onStop($callable)
    {
        $this->onStop[] = $callable;

        return $this;
    }

    /**
     * Catch exception.
     *
     * @param TickInterface $tick
     * @param \Exception $e
     */
    protected function catchTickException(TickInterface $tick, \Exception $e)
    {
        foreach ($this->onException as $callable) {
            call_user_func_array($callable, [new TickException($tick, $e)]);
        }
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
     * Get start at timestamp.
     *
     * @return integer|null
     */
    public function startAt()
    {
        return $this->startAt;
    }

    /**
     * Get stop at timestamp.
     *
     * @return integer|null
     */
    public function stopAt()
    {
        return $this->stopAt;
    }
}