<?php

namespace App\Event\EventListener;

use Symfony\Component\Security\Http\Event\SwitchUserEvent;

class SwitchUserListener
{
    public function onSecuritySwitchUser(SwitchUserEvent $event)
    {
        $event->getRequest()->getSession()->getFlashBag()->add('info', 'Usurpateur');
    }
}