<?php

namespace Gear\Loop\Tick;

/**
 * Pcntl tick.
 *
 * Stop loop on pcntl_signal.
 */
class PcntlTick extends AbstractTick implements TickInterface
{
    /**
     * PcntlTick constructor.
     *
     * @param int $time
     */
    public function __construct($time = 0)
    {
        foreach ([SIGINT, SIGTERM] as $signal) {
            pcntl_signal($signal, [$this, 'handler']);
        }

        parent::__construct('pcntl', $time);
    }

    /**
     * Handle pcntl signal.
     */
    public function handler()
    {
        if ($this->getManager() && $this->getManager()->isStart()) {
            $this->getManager()->stop();
        }
    }

    /**
     * Tick.
     *
     * @return void
     */
    public function tick()
    {
        try {
            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }
        } catch (\Exception $e) {
            $this->catchException($e);
        }
    }
}