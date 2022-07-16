<?php

namespace Lpotherat\Smtp\Server\Event;

/**
 * Class MessageReceivedEvent
 */
class MessageReceivedEvent
{
    /**
     * MessageReceivedEvent constructor.
     * @param string $message
     */
    public function __construct(public readonly string $message){}
}
