<?php

namespace App\Repository;

use App\Entity\ChapterTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ChapterTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChapterTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChapterTranslation[]    findAll()
 * @method ChapterTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChapterTranslation::class);
    }

    // /**
    //  * @return ChapterTranslation[] Returns an array of ChapterTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChapterTranslation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
