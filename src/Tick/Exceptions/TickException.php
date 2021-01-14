<?php

namespace Gear\Loop\Tick\Exceptions;

use Gear\Loop\Tick\TickInterface;

class TickException extends \Exception
{
    protected TickInterface $tick;

    public function __construct(TickInterface $tick, \Throwable $exception)
    {
        $this->tick = $tick;

        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }

    public function getTick(): TickInterface
    {
        return $this->tick;
    }
}
