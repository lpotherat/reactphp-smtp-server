<?php

namespace Lpotherat\Smtp\Server\Event;

use Lpotherat\Smtp\Server\Connection;

/**
 * Class ConnectionHeloReceivedEvent
 */
class ConnectionHeloReceivedEvent
{

    /**
     * ConnectionHeloReceivedEvent constructor.
     * @param string $domain
     */
    public function __construct(public readonly string $domain){}

}
