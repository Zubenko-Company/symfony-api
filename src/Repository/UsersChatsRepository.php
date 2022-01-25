<?php

namespace App\Repository;

use App\Entity\UsersChats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersChats|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersChats|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersChats[]    findAll()
 * @method UsersChats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersChatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersChats::class);
    }

    // /**
    //  * @return UsersChats[] Returns an array of UsersChats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsersChats
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
