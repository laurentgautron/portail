<?php

namespace App\EventListener;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(LogoutEvent $logoutEvent)
    {
        $user = $logoutEvent->getToken()->getUser();
        $user->setLastLog(new \DateTime());
        //dd($user);
        $this->em->persist($user);
        $this->em->flush();
        //dd($user);
    }
}