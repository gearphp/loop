<?php

namespace Gear\Loop\Tick\Exceptions;

use Gear\Loop\Tick\TickInterface;

/**
 * Tick exception.
 */
class TickException extends \Exception
{
    /**
     * Tick interface.
     *
     * @var TickInterface
     */
    protected $tick;

    /**
     * TickException constructor.
     *
     * @param TickInterface $tick
     * @param \Throwable $exception
     */
    public function __construct(TickInterface $tick, \Throwable $exception)
    {
        $this->tick = $tick;

        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Get tick interface.
     *
     * @return TickInterface
     */
    public function getTick()
    {
        return $this->tick;
    }
}
