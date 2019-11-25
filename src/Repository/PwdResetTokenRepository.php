<?php

namespace App\Repository;

use App\Entity\PwdResetToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PwdResetToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method PwdResetToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method PwdResetToken[]    findAll()
 * @method PwdResetToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PwdResetTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PwdResetToken::class);
    }

    // /**
    //  * @return PwdResetToken[] Returns an array of PwdResetToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PwdResetToken
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
