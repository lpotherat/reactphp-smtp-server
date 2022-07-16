<?php

namespace Lpotherat\Smtp\Server\Event;

/**
 * Class ConnectionFromReceivedEvent
 */
class ConnectionFromReceivedEvent
{
    /**
     * ConnectionFromReceivedEvent constructor.
     * @param string $mail
     * @param string|null $name
     */
    public function __construct(public readonly string $mail, public readonly ?string $name = null){}

}
