<?php

namespace App\Repository;

use App\Entity\ChapterPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ChapterPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChapterPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChapterPage[]    findAll()
 * @method ChapterPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChapterPage::class);
    }

    // /**
    //  * @return ChapterPage[] Returns an array of ChapterPage objects
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
    public function findOneBySomeField($value): ?ChapterPage
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
