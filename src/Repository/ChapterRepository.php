<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function getChapterAndChildrenAsArray(Chapter $chapter): array
    {
        // FIXME this query returns an empty array if any child is missing
        // for example, if there is a quiz without any questions or any questions without an answer

        $em = $this->getEntityManager();
        $dql = 'SELECT c, ct, ctl, p, pt, ptl, q, qq, qqt, qqtl, qa, qat, qatl FROM App\Entity\Chapter c JOIN c.translations ct JOIN ct.language ctl JOIN c.pages p JOIN p.translations pt JOIN pt.language ptl JOIN c.quiz q JOIN q.quizQuestions qq JOIN qq.translations qqt JOIN qqt.language qqtl JOIN qq.answers qa JOIN qa.translations qat JOIN qat.language qatl WHERE c.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $chapter->getId());
        return $query->getArrayResult();
    }

    public function getChapterQuizAsArray(Chapter $chapter): array
    {
        // FIXME this query returns an empty array if any child is missing
        // for example, if there is a quiz without any questions or any questions without an answer

        $em = $this->getEntityManager();
        $dql = 'SELECT c, q, qq, qqt, qqtl, qa, qat, qatl FROM App\Entity\Chapter c JOIN c.quiz q JOIN q.quizQuestions qq JOIN qq.translations qqt JOIN qqt.language qqtl JOIN qq.answers qa JOIN qa.translations qat JOIN qat.language qatl WHERE c.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $chapter->getId());
        return $query->getArrayResult();
    }

    public function getChapterAsArray(Chapter $chapter): array
    {
        // FIXME this query returns an empty array if any child is missing
        // for example, if there is a quiz without any questions or any questions without an answer

        $em = $this->getEntityManager();
        $dql = 'SELECT c, ct, ctl, p FROM App\Entity\Chapter c JOIN c.translations ct JOIN ct.language ctl JOIN c.pages p WHERE c.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $chapter->getId());
        return $query->getArrayResult();
    }

    public function getQuizAsArray(Chapter $chapter): array
    {
        // FIXME this query returns an empty array if any child is missing
        // for example, if there is a quiz without any questions or any questions without an answer

        $em = $this->getEntityManager();
        $dql = 'SELECT c, q, qq, qqt, qqtl, qa, qat, qatl FROM App\Entity\Chapter c JOIN c.quiz q JOIN q.quizQuestions qq JOIN qq.translations qqt JOIN qqt.language qqtl JOIN qq.answers qa JOIN qa.translations qat JOIN qat.language qatl WHERE c.id = :id';
        $query = $em->createQuery($dql);
        $query->setParameter(':id', $chapter->getId());
        return $query->getArrayResult();
    }

    // /**
    //  * @return Chapter[] Returns an array of Chapter objects
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
    public function findOneBySomeField($value): ?Chapter
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
