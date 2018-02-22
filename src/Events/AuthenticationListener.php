<?php

namespace SimpleUser\Events;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use SimpleUser\Service\LoginManager;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationListener
{
    /**
     * @var LoginManager
     */
    protected $loginManager;

    /**
     * @var string
     */
    protected $firewallName;

    /**
     * @param LoginManager $loginManager
     */
    public function __construct(LoginManager $loginManager, $firewallName)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
    }

    /**
     * @param AuthenticationEvent $event
     */
    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->loginManager->logInUser(
                $this->firewallName,
                $user
            );
        }
    }
}