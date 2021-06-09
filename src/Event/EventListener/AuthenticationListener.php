<?php

namespace App\Event\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class AuthenticationListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $flashBag = $this->requestStack->getCurrentRequest()->getSession()->getFlashBag();
        if (!$flashBag->has('info')) {
            $flashBag->add('info', 'Bonjour, comment allez-vous ?');
        }
    }
}