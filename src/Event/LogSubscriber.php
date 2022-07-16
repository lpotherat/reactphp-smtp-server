<?php

namespace Smalot\Smtp\Server\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Smalot\Smtp\Server\Events;

/**
 * Class LogSubscriber
 * @package Smalot\Smtp\Server\Event
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
            MessageSentEvent::class => [$this->onMessageSent(...)],
            MessageReceivedEvent::class => [$this->onMessageReceived(...)],
        };
    }

    /**
     * @param ConnectionChangeStateEvent $event
     */
    public function onConnectionChangeState(ConnectionChangeStateEvent $event)
    {
        $this->logger->debug('State changed from '.$event->getOldState().' to '.$event->getNewState());
    }

    /**
     * @param ConnectionHeloReceivedEvent $event
     */
    public function onConnectionHeloReceived(ConnectionHeloReceivedEvent $event)
    {
        $this->logger->debug('Domain: '.$event->getDomain());
    }

    /**
     * @param ConnectionFromReceivedEvent $event
     */
    public function onConnectionFromReceived(ConnectionFromReceivedEvent $event)
    {
        $mail = $event->getMail();
        $name = $event->getName() ?: $mail;
        $this->logger->debug('From: '.$name.' <'.$mail.'>');
    }

    /**
     * @param ConnectionRcptReceivedEvent $event
     */
    public function onConnectionRcptReceived(ConnectionRcptReceivedEvent $event)
    {
        $mail = $event->getMail();
        $name = $event->getName() ?: $mail;
        $this->logger->debug('Rcpt: '.$name.' <'.$mail.'>');
    }

    /**
     * @param ConnectionLineReceivedEvent $event
     */
    public function onConnectionLineReceived(ConnectionLineReceivedEvent $event)
    {
        $this->logger->debug('Line: '.$event->getLine());
    }

    /**
     * @param ConnectionAuthAcceptedEvent $event
     */
    public function onConnectionAuthAccepted(ConnectionAuthAcceptedEvent $event)
    {
        $this->logger->debug('Auth used: '.$event->getAuthMethod()->getType());
        $this->logger->info('User granted: '.$event->getAuthMethod()->getUsername());
    }

    /**
     * @param ConnectionAuthRefusedEvent $event
     */
    public function onConnectionAuthRefused(ConnectionAuthRefusedEvent $event)
    {
        $this->logger->debug('Auth used: '.$event->getAuthMethod()->getType());
        $this->logger->error('User refused: '.$event->getAuthMethod()->getUsername());
    }

    /**
     * @param MessageReceivedEvent $event
     */
    public function onMessageReceived(MessageReceivedEvent $event)
    {
        $this->logger->info('Message received via smtp: '.strlen($event->getMessage()).' bytes');
    }

    /**
     * @param MessageSentEvent $event
     */
    public function onMessageSent(MessageSentEvent $event)
    {
        $this->logger->info('Message sent via sendmail: '.strlen($event->getMessage()).' bytes');
    }

}
