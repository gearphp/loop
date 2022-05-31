<?php

namespace Gear\Loop\Tick;

class CallableTick extends AbstractTick implements TickInterface
{
    protected $callable;

    public function __construct(string $name, callable $callable, int $time = 0)
    {
        $this->callable = $callable;
        parent::__construct($name, $time);
    }

    /**
     * {@inheritDoc}
     */
    public function tick(): void
    {
        try {
            \call_user_func_array($this->callable, [$this]);
        } catch (\Throwable $e) {
            $this->catchException($e);
        }
    }
}
