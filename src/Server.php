<?php

namespace Smalot\Smtp\Server;

use Psr\EventDispatcher\EventDispatcherInterface;
use React\Socket\ConnectionInterface;
use React\Socket\ServerInterface;
use Smalot\Smtp\Server\Auth\MethodInterface;

/**
 * Class Server
 * @package Smalot\Smtp\Server
 */
class Server
{

    /**
     * Server constructor
     * @param ServerInterface $server
     * @param int $recipientLimit
     * @param int $bannerDelay
     * @param array $authMethods
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(
        ServerInterface $server,
        private int $recipientLimit = 100,
        private int $bannerDelay = 0,
        private array $authMethods = [],
        ?EventDispatcherInterface $dispatcher=null)
    {
        $server->on('connection',function(ConnectionInterface $connection) use ($dispatcher){
            new Connection($connection,$this,$dispatcher);
        });
    }

    /**
     * @return int
     */
    public function getRecipientLimit(): int
    {
        return $this->recipientLimit;
    }

    /**
     * @return int
     */
    public function getBannerDelay(): int
    {
        return $this->bannerDelay;
    }

    /**
     * @return array
     */
    public function getAuthMethods(): array
    {
        return $this->authMethods;
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
