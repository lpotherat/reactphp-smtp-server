<?php

namespace Lpotherat\Smtp\Server\Event;

/**
 * Class ConnectionLineReceivedEvent
 */
class ConnectionLineReceivedEvent
{
    /**
     * ConnectionLineReceivedEvent constructor.
     * @param string $line
     */
    public function __construct(public readonly string $line){}
}
