<?php

namespace App\Repository;

use App\Entity\Traductions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Traductions>
 */
class TraductionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traductions::class);
    }

    //    /**
    //     * @return Traductions[] Returns an array of Traductions objects
    //     */
       public function findBySourceId($value): array
       {
           return $this->createQueryBuilder('t')
               ->andWhere('t.Source = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Traductions
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
