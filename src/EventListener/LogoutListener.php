<?php

namespace App\EventListener;

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
        $user->setLogoutAt(new \DateTimeImmutable());
        $uow = $this->em->getUnitOfWork();
        $this->em->persist($user);
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($user);
        //dd($changeset);
        $this->em->flush();
    }
}