<?php

namespace App\Repository;

use App\Entity\Upvote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Upvote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Upvote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Upvote[]    findAll()
 * @method Upvote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpvoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Upvote::class);
    }

    // /**
    //  * @return Upvote[] Returns an array of Upvote objects
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
    public function findOneBySomeField($value): ?Upvote
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
