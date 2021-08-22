<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class LoginListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationEvent $authenticationSuccessEvent)
    {
        $user = $authenticationSuccessEvent->getAuthenticationToken()->getUser();
        $user->setLastLogin(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }
}