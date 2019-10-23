<?php

namespace App\Repository;

use App\Entity\LearningModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LearningModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method LearningModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method LearningModule[]    findAll()
 * @method LearningModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LearningModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LearningModule::class);
    }

    // /**
    //  * @return LearningModule[] Returns an array of LearningModule objects
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
    public function findOneBySomeField($value): ?LearningModule
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
