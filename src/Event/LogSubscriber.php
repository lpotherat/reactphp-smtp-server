<?php

namespace Lpotherat\Smtp\Server\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class LogSubscriber
 */
class LogSubscriber implements ListenerProviderInterface,LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * LogSubscriber constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * @param object $event
     * @return iterable
     */
    public function getListenersForEvent(object $event): iterable
    {
        return match ($event::class){
            ConnectionAuthAcceptedEvent::class => [$this->onConnectionAuthAccepted(...)],
            ConnectionAuthRefusedEvent::class => [$this->onConnectionAuthRefused(...)],
            ConnectionChangeStateEvent::class => [$this->onConnectionChangeState(...)],
            ConnectionFromReceivedEvent::class => [$this->onConnectionFromReceived(...)],
            ConnectionHeloReceivedEvent::class => [$this->onConnectionHeloReceived(...)],
            ConnectionLineReceivedEvent::class => [$this->onConnectionLineReceived(...)],
            ConnectionRcptReceivedEvent::class => [$this->onConnectionRcptReceived(...)],
            MessageReceivedEvent::class => [$this->onMessageReceived(...)],
        };
    }

    /**
     * @param ConnectionChangeStateEvent $event
     */
    public function onConnectionChangeState(ConnectionChangeStateEvent $event)
    {
        $this->logger->debug("State changed from $event->oldState to $event->newState");
    }

    /**
     * @param ConnectionHeloReceivedEvent $event
     */
    public function onConnectionHeloReceived(ConnectionHeloReceivedEvent $event)
    {
        $this->logger->debug("Domain: $event->domain");
    }

    /**
     * @param ConnectionFromReceivedEvent $event
     */
    public function onConnectionFromReceived(ConnectionFromReceivedEvent $event)
    {
        $mail = $event->mail;
        $name = $event->name ?: $mail;
        $this->logger->debug("From: $name <$mail>");
    }

    /**
     * @param ConnectionRcptReceivedEvent $event
     */
    public function onConnectionRcptReceived(ConnectionRcptReceivedEvent $event)
    {
        $mail = $event->mail;
        $name = $event->name ?: $mail;
        $this->logger->debug("Rcpt: $name <$mail>");
    }

    /**
     * @param ConnectionLineReceivedEvent $event
     */
    public function onConnectionLineReceived(ConnectionLineReceivedEvent $event)
    {
        $this->logger->debug("Line: $event->line");
    }

    /**
     * @param ConnectionAuthAcceptedEvent $event
     */
    public function onConnectionAuthAccepted(ConnectionAuthAcceptedEvent $event)
    {
        $this->logger->debug("Auth used: {$event->authMethod->getType()}");
        $this->logger->info("User granted: {$event->authMethod->getUsername()}");
    }

    /**
     * @param ConnectionAuthRefusedEvent $event
     */
    public function onConnectionAuthRefused(ConnectionAuthRefusedEvent $event)
    {
        $this->logger->debug("Auth used: {$event->authMethod->getType()}");
        $this->logger->error("User refused: {$event->authMethod->getUsername()}");
    }

    /**
     * @param MessageReceivedEvent $event
     */
    public function onMessageReceived(MessageReceivedEvent $event)
    {
        $msgLength = strlen($event->message);
        $this->logger->info("Message received via smtp: $msgLength bytes");
    }

}
