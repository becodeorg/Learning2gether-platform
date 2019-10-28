<?php

namespace App\Repository;

use App\Entity\ChapterPageTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ChapterPageTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChapterPageTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChapterPageTranslation[]    findAll()
 * @method ChapterPageTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterPageTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChapterPageTranslation::class);
    }

    // /**
    //  * @return ChapterPageTranslation[] Returns an array of ChapterPageTranslation objects
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
    public function findOneBySomeField($value): ?ChapterPageTranslation
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
