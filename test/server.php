<?php

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use React\Socket\SocketServer;
use Lpotherat\Smtp\Server\Connection;
use Lpotherat\Smtp\Server\Event\LogSubscriber;
use Lpotherat\Smtp\Server\Server;

include '../vendor/autoload.php';

//prevent web execution
if (php_sapi_name() !== 'cli'){
    exit();
}

try {
    // Simple logger to echo all logs in console
    $logger = new class implements LoggerInterface {
        use LoggerTrait;
        public function log($level, string|\Stringable $message, array $context = []): void
        {
            echo "[$level] ".preg_replace_callback(
                    pattern:'/{([a-zA-Z0-9-_]+)}/',
                    callback:function($matches) use ($context){
                        return $context[$matches[1]] ?? $matches[0];
                    },
                    subject: $message
                ).PHP_EOL;
        }
    };

    //Simple dispatcher to listen to all events of the mail server for logging
    $dispatcher = new class(new LogSubscriber($logger)) implements EventDispatcherInterface{
        public function __construct(
            private ?ListenerProviderInterface $listenerProvider = null){}

        public function dispatch(object $event):void
        {
            $listeners = $this->listenerProvider->getListenersForEvent($event);
            /** @var callable $listener */
            foreach ($listeners as $listener) {
                $listener($event);
                if ($event instanceof StoppableEventInterface
                    && $event->isPropagationStopped()) {
                    break;
                }
            }
        }
    };

    //Starts the mail server
    $server = new Server(
        server: new SocketServer(uri: '127.0.0.1:1025'),
        authMethods: [
            Connection::AUTH_METHOD_CRAM_MD5,
            Connection::AUTH_METHOD_PLAIN,
            Connection::AUTH_METHOD_LOGIN,
        ],
        dispatcher: $dispatcher);
}
catch(\Exception $e) {
    var_dump($e);
}