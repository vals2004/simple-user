<?php

namespace SimpleUser\Helpers;


class HashHelper
{
    /**
     * @param $email
     * @return string
     */
    public static function createSalt(string $email): string
    {
        return hash('sha256', $email . uniqid(), false);
    }

    /**
     * @return string
     */
    public static function createConfirmationHash(): string
    {
        return hash('sha256', uniqid(), false);
    }
}