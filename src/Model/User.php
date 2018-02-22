<?php

namespace SimpleUser\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string|null
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string|null
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string|null
     */
    protected $salt;

    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return User
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param null|string $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param null|string $salt
     * @return User
     */
    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string $role
     * @return User
     */
    public function addRole(string $role): self
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
       return (string) $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    /**
     * @param string $serialized
     * @return User
     */
    public function unserialize($serialized): self
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getUsername();
    }
}