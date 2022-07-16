<?php

namespace Lpotherat\Smtp\Server\Event;

/**
 * Class ConnectionChangeStateEvent
 */
class ConnectionChangeStateEvent
{
    /**
     * ConnectionChangeStateEvent constructor.
     * @param string $oldState
     * @param string $newState
     */
    public function __construct(
        public readonly string $oldState,
        public readonly string $newState){}
}
