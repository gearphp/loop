<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\Exceptions\TickException;
use Gear\Loop\Tick\TickInterface;

abstract class AbstractManager implements ManagerInterface
{
    /**
     * Min tick interval (msec).
     */
    protected $interval = 1e4;

    /**
     * Is loop start.
     */
    protected bool $isStart = false;

    /**
     * On start event.
     */
    protected array $onStart = [];

    /**
     * On stop event.
     */
    protected array $onStop = [];

    /**
     * On before tick event.
     */
    protected array $onBeforeTick = [];

    /**
     * On after tick event.
     */
    protected array $onAfterTick = [];

    /**
     * On exception event.
     */
    protected array $onException = [];

    /**
     * {@inheritDoc}
     */
    public function start(): void
    {
        if ($this->isStart) {
            return;
        }

        $this->isStart = true;

        if (\extension_loaded('xdebug')) {
            xdebug_disable();
        }

        foreach ($this->onStart as $callable) {
            \call_user_func_array($callable, []);
        }

        while ($this->isStart) {
            foreach ($this->onBeforeTick as $callable) {
                \call_user_func_array($callable, []);
            }

            $time = $this->tick();

            foreach ($this->onAfterTick as $callable) {
                \call_user_func_array($callable, []);
            }

            if ($time < $this->interval) {
                \usleep($this->interval - $time);
            }
        }

        foreach ($this->onStop as $callable) {
            \call_user_func_array($callable, []);
        }
    }

    abstract protected function tick(): int;

    /**
     * {@inheritDoc}
     */
    public function stop(): void
    {
        $this->isStart = false;
    }

    /**
     * {@inheritDoc}
     */
    public function isStart(): bool
    {
        return $this->isStart;
    }

    /**
     * {@inheritDoc}
     */
    public function onStart(callable $callable): self
    {
        $this->onStart[] = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeTick(callable $callable): self
    {
        $this->onBeforeTick[] = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterTick(callable $callable): self
    {
        $this->onAfterTick[] = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onStop(callable $callable): self
    {
        $this->onStop[] = $callable;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * {@inheritDoc}
     */
    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Catch exception.
     */
    protected function catchTickException(TickInterface $tick, \Throwable $e): void
    {
        foreach ($this->onException as $callable) {
            \call_user_func_array($callable, [new TickException($tick, $e)]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onException(callable $callable): self
    {
        $this->onException[] = $callable;

        return $this;
    }
}
