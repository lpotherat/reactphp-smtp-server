<?php

namespace Smalot\Smtp\Server\Auth;

/**
 * Interface MethodInterface
 * @package Smalot\Smtp\Server\Auth
 */
interface MethodInterface
{
    /**
     * @return string
     */
    public function getType():string;

    /**
     * @return string|null
     */
    public function getUsername():?string;

    /**
     * @return string|null
     */
    public function getPassword():?string;

    /**
     * @param string $password
     * @return bool
     */
    public function validateIdentity(string $password):bool;
}
