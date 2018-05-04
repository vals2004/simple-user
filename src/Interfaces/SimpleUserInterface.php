<?php

namespace SimpleUser\Interfaces;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

interface SimpleUserInterface extends AdvancedUserInterface, \Serializable, EquatableInterface
{
    /**
     * @param SimpleUserRoleInterface $role
     * @return SimpleUserInterface
     */
    public function addRole(SimpleUserRoleInterface $role): self;

    /**
     * @param mixed $role
     * @return SimpleUserInterface
     */
    public function removeRole($role): self;

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
    public function setPassword(?string $password): ?self;

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

    /**
     * @param bool $isEnabled
     * @return SimpleUserInterface
     */
    public function setIsEnabled(bool $isEnabled): self;

    /**
     * @return null|string
     */
    public function getPasswordResetHash(): ?string;

    /**
     * @param null|string $passwordResetHash
     * @return SimpleUserInterface
     */
    public function setPasswordResetHash(?string $passwordResetHash): self;
}
