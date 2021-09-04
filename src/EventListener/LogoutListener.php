<?php

namespace App\EventListener;

use App\Entity\Lastconnexion;
use App\Repository\LastconnexionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    public function __construct(EntityManagerInterface $em, LastconnexionRepository $lastconnexionRepository)
    {
        $this->em = $em;
        $this->lastconnexionRepository = $lastconnexionRepository;
    }

    public function __invoke(LogoutEvent $logoutEvent)
    {
        $user = $logoutEvent->getToken()->getUser();
        $last = $this->lastconnexionRepository->findByUser($user->getId());
        if ($last == []) {
            $thelast = new Lastconnexion;
            $thelast->setUser($user);
            $thelast->setLasLogoutAt(new \DateTimeImmutable());
            $this->em->persist($thelast);
        } else {
            $last[0]->setLasLogoutAt(new \DateTimeImmutable());
        }
        
        // $uow = $this->em->getUnitOfWork();
        // $this->em->persist($user);
        // $uow->computeChangeSets();
        // $changeset = $uow->getEntityChangeSet($user);
        $this->em->flush();
    }
}