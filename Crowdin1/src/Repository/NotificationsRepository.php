<?php

namespace App\Repository;

use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notifications>
 */
class NotificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notifications::class);
    }

    //    /**
    //     * @return Notifications[] Returns an array of Notifications objects
    //     */
       public function findByChatAndAuthor($value1, $value2): array
       {
           return $this->createQueryBuilder('n')
               ->Where('n.chat_id = :value1 ')
               ->andWhere('n.author_id = :value2 ')
               ->setParameter('value1', $value1)
               ->setParameter('value2', $value2)
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Notifications
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
