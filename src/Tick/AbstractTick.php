<?php

namespace Gear\Loop\Tick;

use Gear\Loop\Manager\ManagerInterface;

abstract class AbstractTick implements TickInterface
{
    protected ?ManagerInterface $manager = null;
    protected string $name;
    protected int $interval;
    protected array $onException = [];

    public function __construct(string $name, int $interval = 1)
    {
        $this->name = $name;
        $this->setInterval($interval);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setManager(?ManagerInterface $manager): self
    {
        if ($this->manager) {
            $this->manager->removeTick($this->name);
        }

        $this->manager = $manager;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getManager(): ?ManagerInterface
    {
        return $this->manager;
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
        $this->interval = (int) $interval >= 0 ? (int) $interval : 0;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function onException(callable $callable): self
    {
        $this->onException[] = $callable;

        return $this;
    }

    /**
     * Catch tick exception and throw it.
     */
    protected function catchException(\Throwable $e)
    {
        foreach ($this->onException as $call) {
            \call_user_func_array($call, [$e]);
        }

        throw $e;
    }
}
