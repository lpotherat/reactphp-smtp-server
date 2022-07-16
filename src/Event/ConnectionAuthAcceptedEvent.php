<?php

namespace Lpotherat\Smtp\Server\Event;

use Lpotherat\Smtp\Server\Auth\MethodInterface;

/**
 * Class ConnectionAuthAcceptedEvent
 */
class ConnectionAuthAcceptedEvent
{
    /**
     * ConnectionAuthAcceptedEvent constructor.
     * @param MethodInterface $authMethod
     */
    public function __construct(public readonly MethodInterface $authMethod){}

}
