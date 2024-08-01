<?php

namespace App\Repository;

use App\Entity\Chats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chats>
 */
class ChatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chats::class);
    }

    //    /**
    //     * @return Chats[] Returns an array of Chats objects
    //     */
       public function findById($value): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.id = :val')
               ->setParameter('val', $value)
               ->getQuery()
               ->getResult()
           ;
       }

       public function findByUser($value): array
       {
           return $this->createQueryBuilder('c')
               ->where('c.user1 = :value')
               ->orWhere('c.user2 = :value')
               ->setParameter('value', $value)
               ->orderBy('c.id', 'ASC')
               ->getQuery()
               ->getResult();
       }
}
