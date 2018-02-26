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
     * @param SimpleUserRoleInterface $role
     * @return SimpleUserInterface
     */
    public function removeRole(SimpleUserRoleInterface $role): self;

    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     * @return SimpleUserInterface
     */
    public function setEmail(string $email): self;

    /**
     * @param null|string $password
     * @return SimpleUserInterface
     */
    public function setPassword(?string $password): self;

    /**
     * @param null|string $salt
     * @return SimpleUserInterface
     */
    public function setSalt(?string $salt): self;

    /**
     * @param null|string $hash
     * @return SimpleUserInterface
     */
    public function setConfirmHash(?string $hash): self;
}