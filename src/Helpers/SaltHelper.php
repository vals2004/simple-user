<?php

namespace SimpleUser\Helpers;


class SaltHelper
{
    /**
     * @param $email
     * @return string
     */
    public static function createSalt(string $email): string
    {
        return hash('sha256', $email . uniqid(), false);
    }
}