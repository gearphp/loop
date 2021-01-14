<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\TickInterface;

class WhileManager extends AbstractManager
{
    /**
     * Ticks interface.
     */
    protected array $ticks = [];

    /**
     * {@inheritDoc}
     */
    public function addTick(TickInterface $tick): self
    {
        $tick->setManager($this);
        $this->ticks[$tick->getName()] = [
            'tick' => $tick,
            'time' => 0,
        ];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeTick($name): self
    {
        if (isset($this->ticks[$name])) {
            $this->ticks[$name]->setManager(null);
            unset($this->ticks[$name]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function tick(): int
    {
        $time = \microtime(true);
        foreach ($this->ticks as $name => $value) {
            /** @var TickInterface $tick */
            $tick = $value['tick'];
            $diff = $time - $value['time'];

            if ($diff >= $tick->getInterval()) {
                try {
                    $tick->tick();
                } catch (\Exception $e) {
                    $this->catchTickException($tick, $e);
                }

                $this->ticks[$name]['time'] = $time;
            }
        }

        return \round((\microtime(true) - $time) * 1e6);
    }
}
