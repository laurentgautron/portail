<?php

namespace App\EventListener;

use DateTime;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function __invoke(LogoutEvent $logoutEvent)
    {
        $user = $logoutEvent->getToken()->getUser();
        $user->setLastLogin(new DateTime());
        dd($user);
    }
}