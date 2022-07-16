<?php

namespace Lpotherat\Smtp\Server\Auth;

/**
 * Interface MethodInterface
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
