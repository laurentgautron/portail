<?php

namespace App\Repository;

use App\Entity\UserCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCompetences[]    findAll()
 * @method UserCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCompetences::class);
    }

     /**
      * @return UserCompetences[] Returns an array of UserCompetences objects
      */
    public function searchComp($comp)
    {
        return $this->createQueryBuilder('uc')
            ->andWhere('uc.competence = :val')
            ->setParameter('val', $comp)
            ->getQuery()
            ->getResult()
        ;
    }

    public function searchCompNiv($comp, $niv)
    {
        return $this->createQueryBuilder('uc')
            ->Where('uc.competence = :val')
            ->andWhere('uc.niveau = :niv')
            ->setParameter('val',$comp)
            ->setParameter('niv', $niv)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserCompetences
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
