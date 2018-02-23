<?php

namespace SimpleUser\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

interface SimpleUserInterface extends UserInterface, \Serializable, EquatableInterface
{
    /**
     * @param SimpleUserRoleInterface $role
     * @return SimpleUserInterface
     */
    public function addRole(SimpleUserRoleInterface $role): self;

    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     * @return SimpleUserInterface
     */
    public function setEmail(string $email): self;
}