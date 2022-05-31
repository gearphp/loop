<?php

namespace Gear\Loop\Manager;

use Gear\Loop\Tick\TickInterface;

class EventManager extends AbstractManager
{
    private \EventBase $eventBase;
    private array $events = [];

    public function __construct()
    {
        if (!\class_exists('EventBase', false)) {
            throw new \Exception('Event extension must be install');
        }

        $this->eventBase = new \EventBase();
    }

    /**
     * {@inheritDoc}
     */
    public function addTick(TickInterface $tick): self
    {
        $tick->setManager($this);

        $event = new \Event($this->eventBase, -1, \Event::TIMEOUT | \Event::PERSIST, function () use ($tick) {
            try {
                $tick->tick();
            } catch (\Throwable $e) {
                $this->catchTickException($tick, $e);
            }
        });

        $event->addTimer($tick->getInterval() < 1 ? 1 : $tick->getInterval());

        $this->events[$tick->getName()] = [
            'event' => $event,
            'tick' => $tick,
        ];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeTick($name): self
    {
        if (isset($this->ticks[$name])) {
            $this->events[$name]['event']->delTimer();
            $this->events[$name]['event']->free();
            $this->events[$name]['tick']->setManager(null);
            unset($this->events[$name]);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function tick(): int
    {
        $time = \microtime(true);
        $this->eventBase->loop(\EventBase::LOOP_NONBLOCK);

        return \round((\microtime(true) - $time) * 1e6);
    }
}
