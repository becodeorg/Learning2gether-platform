<?php

namespace App\Repository;

use App\Entity\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method QuizQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestion[]    findAll()
 * @method QuizQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

    public function getQuestionsAsArray(QuizQuestion $question) : array
    {
        // FIXME this query returns an empty array if any child is missing
        // for example, if there is a quiz without any questions or any questions without an answer

        $em = $this->getEntityManager();
        $dql = 'SELECT qq, qqt, qqtl, qa, qat, qatl FROM App\Entity\QuizQuestion qq JOIN qq.translations qqt JOIN qqt.language qqtl JOIN qq.answers qa JOIN qa.translations qat JOIN qat.language qatl WHERE qq.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $question->getId());
        return $query->getArrayResult();
    }

    // /**
    //  * @return QuizQuestion[] Returns an array of QuizQuestion objects
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

    public function findNumberOfQuestionsForGivenID(int $id) : int
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.quiz = :id')
            ->setParameter('id', $id)
            ->select('count(q)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?QuizQuestion
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
