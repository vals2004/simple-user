<?php

namespace SimpleUser\Events;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use SimpleUser\Helpers\HashHelper;
use SimpleUser\Interfaces\SimpleUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserPasswordSubscriber implements EventSubscriber
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
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
        if (!$entity instanceof SimpleUserInterface) {
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
        if (!$entity instanceof SimpleUserInterface) {
            return;
        }
        $entity->setConfirmHash(HashHelper::createConfirmationHash());
        $this->encodePassword($entity);
    }

    /**
     * @param UserInterface $user
     */
    private function encodePassword(UserInterface $user)
    {
        if (empty($user->getPassword())) {
            return;
        }

        $salt = $user->getSalt();

        if (empty($salt)) {
            $salt = HashHelper::createSalt($user->getEmail());
            $user->setSalt($salt);
        }

        $hash = $this->encoderFactory->getEncoder($user)->encodePassword($user->getPassword(), $salt);
        $user->setPassword($hash);
    }
}
