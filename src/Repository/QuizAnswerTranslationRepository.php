<?php

namespace App\Repository;

use App\Entity\QuizAnswerTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuizAnswerTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizAnswerTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizAnswerTranslation[]    findAll()
 * @method QuizAnswerTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizAnswerTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizAnswerTranslation::class);
    }

    // /**
    //  * @return QuizAnswerTranslation[] Returns an array of QuizAnswerTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizAnswerTranslation
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
