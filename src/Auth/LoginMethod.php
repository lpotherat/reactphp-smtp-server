<?php

namespace Smalot\Smtp\Server\Auth;

/**
 * Class LoginMethod
 * @package Smalot\Smtp\Server\Auth
 */
class LoginMethod implements MethodInterface
{

    /**
     * LoginMethod constructor.
     */
    public function __construct(
        protected ?string $username=null,
        protected ?string $password=null
    ){}

    /**
     * @return string
     */
    public function getType():string
    {
        return 'LOGIN';
    }

    /**
     * @return string|null
     */
    public function getUsername():?string
    {
        return $this->username;
    }

    /**
     * @param string $user
     * @return $this
     */
    public function setUsername(string $user):static
    {
        $this->username = base64_decode($user);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword():?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password):static
    {
        $this->password = base64_decode($password);

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
