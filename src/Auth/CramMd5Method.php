<?php

namespace Smalot\Smtp\Server\Auth;

use JetBrains\PhpStorm\Pure;

/**
 * Class CramMd5Method
 * @package Smalot\Smtp\Server\Auth
 */
class CramMd5Method implements MethodInterface
{
    /**
     * @var string
     */
    protected string $challenge;

    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $password;

    /**
     * LoginMethod constructor.
     */
    public function __construct()
    {
        $this->challenge = $this->generateChallenge();
    }

    /**
     * @return string
     */
    protected function generateChallenge(): string
    {
        $random = openssl_random_pseudo_bytes(32);
        return '<'.bin2hex($random).'@react-smtp.tld>';
    }

    /**
     * @return string
     */
    public function getType():string
    {
        return 'CRAM-MD5';
    }

    /**
     * @return string
     */
    public function getChallenge():string
    {
        return base64_encode($this->challenge);
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
        list($username, $password) = explode(' ', base64_decode($token));

        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validateIdentity(string $password):bool
    {
        $hashMd5 = $this->_hmacMd5($password, $this->challenge);

        return $hashMd5 === $this->password;
    }

    /**
     * @see https://github.com/AOEpeople/Menta_GeneralComponents/blob/master/lib/Zend/Mail/Protocol/Smtp/Auth/Crammd5.php
     *
     * @param string $key
     * @param string $data
     * @param int $block
     * @return string
     */
    protected function _hmacMd5(string $key, string $data, int $block = 64):string
    {
        if (strlen($key) > 64) {
            $key = pack('H32', md5($key));
        } elseif (strlen($key) < 64) {
            $key = str_pad($key, $block, "\0");
        }
        $k_ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36), 64);
        $k_opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64);
        $inner = pack('H32', md5($k_ipad.$data));
        return md5($k_opad.$inner);
    }
}
