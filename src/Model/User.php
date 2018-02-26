<?php

namespace SimpleUser\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use SimpleUser\Interfaces\SimpleUserInterface;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity("email")
 */
abstract class User implements SimpleUserInterface
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
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     * @Assert\Email()
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
     * @ORM\Column(type="string", name="confirm_hash",length=255, nullable=true)
     *
     * @var string|null
     */
    protected $confirmHash;

    /**
     * @ORM\Column(type="string", name="password_reset_hash",length=255, nullable=true)
     *
     * @var string|null
     */
    protected $passwordResetHash;

    /**
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @ORM\Column(type="boolean", name="is_enabled", nullable=false, options={"default" : 0})
     * @var bool
     */
    protected $isEnabled = false;

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
    public function setEmail(?string $email): SimpleUserInterface
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
     * @return SimpleUserInterface
     */
    public function setPassword(?string $password): SimpleUserInterface
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
     * @return SimpleUserInterface
     */
    public function setSalt(?string $salt): SimpleUserInterface
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $data = [];
        /** @var SimpleUserRoleInterface $role */
        foreach ($this->roles as $role) {
            $data[$role->getId()] = strtoupper($role->getName());
        }
        return $data;
    }

    /**
     * @return ArrayCollection
     */
    public function getRolesCollection()
    {
        return $this->roles;
    }

    /**
     * @param SimpleUserRoleInterface $role
     * @return User
     */
    public function addRole(SimpleUserRoleInterface $role): SimpleUserInterface
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addUser($this);
        }

        return $this;
    }

    /**
     * @param SimpleUserRoleInterface $role
     * @return SimpleUserInterface
     */
    public function removeRole($role): SimpleUserInterface
    {
        if ($role instanceof SimpleUserRoleInterface) {
            if ($this->roles->contains($role)) {
                $this->roles->removeElement($role);
                $role->removeUser($this);
            }
        } else {
            foreach ($this->roles as $roleDb) {
                if ($roleDb->getName() === $role) {
                    $this->removeRole($roleDb);
                    break;
                }
            }
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
        $this->password = null;
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

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($this->email !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return null|string
     */
    public function getConfirmHash(): ?string
    {
        return $this->confirmHash;
    }

    /**
     * @param null|string $confirmHash
     * @return SimpleUserInterface
     */
    public function setConfirmHash(?string $confirmHash): SimpleUserInterface
    {
        $this->confirmHash = $confirmHash;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return false
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return SimpleUserInterface
     */
    public function setEnabled(bool $isEnabled): SimpleUserInterface
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPasswordResetHash(): ?string
    {
        return $this->passwordResetHash;
    }

    /**
     * @param null|string $passwordResetHash
     * @return SimpleUserInterface
     */
    public function setPasswordResetHash(?string $passwordResetHash): SimpleUserInterface
    {
        $this->passwordResetHash = $passwordResetHash;

        return $this;
    }
}