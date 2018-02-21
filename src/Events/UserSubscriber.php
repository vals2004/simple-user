<?php

namespace SimpleUser\Events;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SimpleUser\Model\User as User;

class UserSubscriber implements EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * Constructor
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        if (!$args->hasChangedField('password')) {
            return;
        }

        $password = $entity->getPassword();
        $oldPassword = $args->getOldValue('password');

        if ($password === $oldPassword) {
            return;
        }

        $this->encodePassword($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    /**
     * @param User $entity
     */
    private function encodePassword(User $entity)
    {
        if (empty($entity->getPassword())) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
        $entity->setPassword($encoded);
    }
}
