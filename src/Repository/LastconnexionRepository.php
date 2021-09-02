<?php

namespace App\Repository;

use App\Entity\Lastconnexion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lastconnexion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lastconnexion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lastconnexion[]    findAll()
 * @method Lastconnexion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LastconnexionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lastconnexion::class);
    }

    // /**
    //  * @return Lastconnexion[] Returns an array of Lastconnexion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lastconnexion
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
