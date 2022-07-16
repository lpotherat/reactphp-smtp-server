<?php

namespace Smalot\Smtp\Server;

use Psr\EventDispatcher\EventDispatcherInterface;
use Smalot\Smtp\Server\Event\MessageSentEvent;

/**
 * Class Sendmail
 * @package Smalot\Smtp\Server
 */
class Sendmail
{
    /**
     * @var EventDispatcherInterface|null
     */
    protected ?EventDispatcherInterface $dispatcher;

    /**
     * Sendmail constructor.
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(?EventDispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return bool
     */
    public function run(): bool
    {
        if (0 === ftell(STDIN)) {
            $message = '';

            while (!feof(STDIN)) {
                $message .= fread(STDIN, 1024);
            }

            $this->dispatcher?->dispatch(new MessageSentEvent($this, $message));

            return true;
        }

        return false;
    }
}
