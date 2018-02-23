<?php

namespace SimpleUser\Service;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Helpers\HashHelper;
use SimpleUser\Interfaces\SimpleUserInterface;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
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
     * @var string
     */
    protected $simpleUserRoleClass;

    /**
     * @param EntityManagerInterface $em
     * @param string $simpleUserClass
     * @param string $simpleUserRoleClass
     */
    public function __construct(EntityManagerInterface $em, string $simpleUserClass, string $simpleUserRoleClass)
    {
        $this->simpleUserClass = $simpleUserClass;
        $this->em = $em;
        $this->simpleUserRoleClass = $simpleUserRoleClass;
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
        $user->setSalt(HashHelper::createSalt($email));
        foreach ($roles as $role) {
            $roleEntity = $this->getRoleOrCreate($role);
            if ($roleEntity) {
                $user->addRole($roleEntity);
            }
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param string $email
     * @param array $roles
     * @return bool
     */
    public function addRolesToUser(string $email, array $roles): bool
    {
        /** @var SimpleUserInterface $user */
        $user = $this->em->getRepository($this->simpleUserClass)->findOneBy(['email' => $email]);
        if (!$user) {
            return false;
        }
        foreach ($roles as $role) {
            $roleClass = $this->getRoleOrCreate($role);
            $user->addRole($roleClass);
        }

        $this->em->flush();

        return true;
    }

    public function removeRolesFromUser(string $email, array $roles): bool
    {
        /** @var SimpleUserInterface $user */
        $user = $this->em->getRepository($this->simpleUserClass)->findOneBy(['email' => $email]);
        if (!$user) {
            return false;
        }
        /** @var SimpleUserRoleInterface $role */
        foreach($user->getRoles() as $role) {
            $roleEntity = $this->getRoleOrCreate($role);

            foreach ($roles as $key => $roleString) {
                if ($roleEntity->getName() === trim($roleString)) {
                    $user->removeRole($roleEntity);
                    unset($roles[$key]);
                }
            }
        }

        $this->em->flush();

        return true;
    }

    /**
     * @param string $roleName
     * @return SimpleUserRoleInterface
     */
    protected function getRoleOrCreate(string $roleName): SimpleUserRoleInterface
    {
        $role = $this->em->getRepository($this->simpleUserRoleClass)->findOneBy(['name' => $roleName]);
        if ($role) {
            return $role;
        }
        /** @var SimpleUserRoleInterface $role */
        $role = new $this->simpleUserRoleClass();
        $role->setName($roleName);
        $role->setDescription('Auto generated role.');

        $this->em->persist($role);
        $this->em->flush();

        return $role;
    }
}