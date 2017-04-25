<?php

namespace Gear\Loop\Tick;

class CallableTick extends AbstractTick implements TickInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     * @param int $time
     */
    public function __construct(callable $callable, $time = 0)
    {
        $this->callable = $callable;
        parent::__construct($time);
    }

    /**
     * Tick.
     *
     * @return void
     */
    public function tick()
    {
        try {
            call_user_func_array($this->callable, [$this]);
        } catch (\Exception $e) {
            $this->catchException($e);
        }
    }
}