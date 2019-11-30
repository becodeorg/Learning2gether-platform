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

    public function getModuleAsArray(LearningModule $module) : array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT l, lt, ltl, c, ct, ctl, p, pt, ptl, q, qq, qqt, qqtl, qa, qat, qatl FROM App\Entity\LearningModule l JOIN l.translations lt JOIN lt.language ltl JOIN l.chapters c JOIN c.translations ct JOIN ct.language ctl JOIN c.pages p JOIN p.translations pt JOIN pt.language ptl JOIN c.quiz q JOIN q.quizQuestions qq JOIN qq.translations qqt JOIN qqt.language qqtl JOIN qq.answers qa JOIN qa.translations qat JOIN qat.language qatl WHERE l.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $module->getId());
        return $query->getArrayResult();
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
