<?php

namespace Lpotherat\Smtp\Server;

use Psr\EventDispatcher\EventDispatcherInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use Lpotherat\Smtp\Server\Auth\MethodInterface;

/**
 * Class Server
 */
class Server
{

    /**
     * Server constructor
     * @param ServerInterface $server
     * @param int $recipientLimit
     * @param int $bannerDelay
     * @param array $authMethods
     * @param string $banner
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(
        ServerInterface $server,
        public readonly int $recipientLimit = 100,
        public readonly int $bannerDelay = 0,
        public readonly array $authMethods = [],
        public readonly string $banner = "Welcome to ReactPHP SMT Server",
        ?EventDispatcherInterface $dispatcher=null)
    {
        $server->on('connection',function(ConnectionInterface $connection) use ($dispatcher){
            new Connection($connection,$this,$dispatcher);
        });
    }

    /**
     * @param Connection $connection
     * @param MethodInterface $method
     * @return bool
     */
    public function checkAuth(Connection $connection, MethodInterface $method): bool
    {
        return true;
    }
}
