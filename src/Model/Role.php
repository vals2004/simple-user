<?php

namespace SimpleUser\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SimpleUser\Interfaces\SimpleUserInterface;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity("name")
 */
abstract class Role implements SimpleUserRoleInterface
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
     * @var string|null
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string|null
     */
    protected $description;

    /**
     * @var ArrayCollection
     */
    protected $users;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Role
     */
    public function setName(?string $name): SimpleUserRoleInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return Role
     */
    public function setDescription(?string $description): SimpleUserRoleInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param SimpleUserInterface $user
     * @return SimpleUserRoleInterface
     */
    public function addUser(SimpleUserInterface $user): SimpleUserRoleInterface
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRole($this);
        }

        return $this;
    }

    /**
     * @param SimpleUserInterface $user
     * @return SimpleUserRoleInterface
     */
    public function removeUser(SimpleUserInterface $user): SimpleUserRoleInterface
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeRole($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }
}