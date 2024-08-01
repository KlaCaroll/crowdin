<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Messages>
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    //    /**
    //     * @return Messages[] Returns an array of Messages objects
    //     */
       public function findByChat($value): array
       {
           return $this->createQueryBuilder('m')
               ->andWhere('m.chat = :val')
               ->setParameter('val', $value)
               ->orderBy('m.id', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }

       public function findById($value): array
       {
           return $this->createQueryBuilder('m')
               ->andWhere('m.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getResult()
           ;
       }
}
