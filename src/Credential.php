<?php

namespace Lixus\ValueFirst;

class Credential
{
    /* @var string $username */
    private $username;

    /* @var string $password */
    private $password;

    /* @var string $senderPhone*/
    private $senderPhone;

    /**
     * Credential constructor.
     * @param string $username
     * @param string $password
     * @param string $senderPhone
     */
    public function __construct(string $username, string $password, string $senderPhone)
    {
        $this->username = $username;
        $this->password = $password;
        $this->senderPhone = $senderPhone;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSenderPhone(): string
    {
        return $this->senderPhone;
    }

}