<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\LearningModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

     /**
      * @return Category[] Returns an array of Category objects
      */
    public function findByType(string $type)
    {
        $qb = $this->createQueryBuilder('c')
            ->join(LearningModule::class, 'l', Join::WITH, 'l = c.learning_module')
            ->where('l.type = :type')
            ->andwhere('l.isPublished = :isPublished')
            ->setParameter('type', $type)
            ->setParameter('isPublished', true);


        return $qb->getQuery()->getResult();
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findAllPublished()
    {


        $qb = $this->createQueryBuilder('c')
            ->join(LearningModule::class, 'l', Join::WITH, 'l = c.learning_module')
            ->where('l.isPublished = :isPublished')
            ->setParameter('isPublished', true);

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
