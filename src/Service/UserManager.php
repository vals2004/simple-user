<?php

namespace SimpleUser\Service;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Helpers\SaltHelper;
use SimpleUser\Model\User;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $simpleUserClass;

    /**
     * @param EntityManagerInterface $em
     * @param string $simpleUserClass
     */
    public function __construct(EntityManagerInterface $em, $simpleUserClass)
    {
        $this->simpleUserClass = $simpleUserClass;
        $this->em = $em;
    }

    /**
     * @param string $email
     * @param string $password
     * @param array $roles
     */
    public function createUser(string $email, string $password, array $roles)
    {
        /** @var User $user */
        $user = new $this->simpleUserClass();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setSalt(SaltHelper::createSalt($email));
        foreach ($roles as $role) {
            $user->addRole($role);
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}