<?php

namespace App\Repository;

use App\Entity\Langues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Langues>
 */
class LanguesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Langues::class);
    }

    //    /**
    //     * @return Langues[] Returns an array of Langues objects
    //     */
       public function findByUser($value): array
       {
           return $this->createQueryBuilder('l')
               ->andWhere('l.user = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Langues
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
