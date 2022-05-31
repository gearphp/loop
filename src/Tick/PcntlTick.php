<?php

namespace Gear\Loop\Tick;

class PcntlTick extends AbstractTick implements TickInterface
{
    public function __construct(int $time = 1)
    {
        foreach ([SIGINT, SIGTERM] as $signal) {
            \pcntl_signal($signal, [$this, 'handler']);
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
     * {@inheritDoc}
     */
    public function tick(): void
    {
        try {
            if (\function_exists('pcntl_signal_dispatch')) {
                \pcntl_signal_dispatch();
            }
        } catch (\Throwable $e) {
            $this->catchException($e);
        }
    }
}
