<?php

namespace Smalot\Smtp\Server\Event;

abstract class Event implements \Psr\EventDispatcher\StoppableEventInterface
{

    /**
     * @inheritDoc
     */
    public function isPropagationStopped(): bool
    {
        return false;
    }
}