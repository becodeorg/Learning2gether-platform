<?php

namespace App\Repository;

use App\Entity\LearningModuleTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LearningModuleTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LearningModuleTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LearningModuleTranslation[]    findAll()
 * @method LearningModuleTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LearningModuleTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LearningModuleTranslation::class);
    }

    // /**
    //  * @return LearningModuleTranslations[] Returns an array of LearningModuleTranslations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LearningModuleTranslations
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
