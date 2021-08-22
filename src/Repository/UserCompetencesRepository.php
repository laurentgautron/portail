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
    public function searchComp($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.competence = :val')
            ->setParameter('val', $value)
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
