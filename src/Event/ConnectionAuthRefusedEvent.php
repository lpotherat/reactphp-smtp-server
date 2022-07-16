<?php

namespace Lpotherat\Smtp\Server\Event;

use Lpotherat\Smtp\Server\Auth\MethodInterface;

/**
 * Class ConnectionAuthRefusedEvent
 */
class ConnectionAuthRefusedEvent
{
    /**
     * ConnectionAuthRefusedEvent constructor.
     * @param MethodInterface $authMethod
     */
    public function __construct(public readonly MethodInterface $authMethod){}
}
