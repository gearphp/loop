<?php

namespace Gear\Loop\Tick;

/**
 * Simple callable tick.
 */
class CallableTick extends AbstractTick implements TickInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * CallableTick constructor.
     *
     * @param string $name
     * @param callable $callable
     * @param int $time
     */
    public function __construct($name, $callable, $time = 0)
    {
        $this->callable = $callable;
        parent::__construct($name, $time);
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