<?php

namespace SimpleUser\Interfaces;


interface SimpleUserRoleInterface
{
    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param null|string $name
     * @return SimpleUserRoleInterface
     */
    public function setName(?string $name): SimpleUserRoleInterface;

    /**
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * @param null|string $description
     * @return SimpleUserRoleInterface
     */
    public function setDescription(?string $description): SimpleUserRoleInterface;

    /**
     * @param SimpleUserInterface $user
     * @return SimpleUserRoleInterface
     */
    public function addUser(SimpleUserInterface $user): SimpleUserRoleInterface;

    /**
     * @param SimpleUserInterface $user
     * @return SimpleUserRoleInterface
     */
    public function removeUser(SimpleUserInterface $user): SimpleUserRoleInterface;
}