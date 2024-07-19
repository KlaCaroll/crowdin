<?php

namespace App\Repository;

use App\Entity\Sources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sources>
 */
class SourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sources::class);
    }

    //    /**
    //     * @return Sources[] Returns an array of Sources objects
    //     */
       public function findByProjectId($value): array
       {
           return $this->createQueryBuilder('s')
               ->andWhere('s.project_id = :value')
               ->setParameter('value', $value)
               ->getQuery()
               ->getResult()
           ;
       }

       public function findByID($value): ?Sources
       {
           return $this->createQueryBuilder('s')
               ->andWhere('s.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
