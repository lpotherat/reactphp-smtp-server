<?php

namespace Smalot\Smtp\Server\Auth;

/**
 * Class PlainMethod
 * @package Smalot\Smtp\Server\Auth
 */
class PlainMethod implements MethodInterface
{
    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $password;

    /**
     * PlainMethod constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getType():string
    {
        return 'PLAIN';
    }

    /**
     * @return string
     */
    public function getUsername():string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword():string
    {
        return $this->password;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function decodeToken(string $token):static
    {
        $parts = explode("\000", base64_decode($token));

        $this->username = $parts[1];
        $this->password = $parts[2];

        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validateIdentity(string $password):bool
    {
        return $password == $this->password;
    }
}
