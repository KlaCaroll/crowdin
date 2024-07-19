<?php

namespace App\Repository;

use App\Entity\Projects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projects>
 */
class ProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projects::class);
    }

    //    /**
    //     * @return Projects[] Returns an array of Projects objects
    //     */
       public function findByUser($value): array
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.user = :value')
               ->setParameter('value', $value)
               ->getQuery()
               ->getResult(); 
       }

       public function findProjectById($value): ?Projects
       {
           return $this->createQueryBuilder('p')
               ->andWhere('p.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }

}
