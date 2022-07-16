<?php

namespace Lpotherat\Smtp\Server\Event;

/**
 * Class ConnectionRcptReceivedEvent
 */
class ConnectionRcptReceivedEvent
{
    /**
     * ConnectionRcptReceivedEvent constructor.
     * @param string $mail
     * @param string $name
     */
    public function __construct(public readonly string $mail, public readonly  string $name){}
}
