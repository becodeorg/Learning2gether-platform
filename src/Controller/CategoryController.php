<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/forum/{category}", name="category", requirements={
     *     "category"="\d+",
     *
     *     })
     */
    public function index(Request $request, Category $category)
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);
        $topics = $this->getDoctrine()->getRepository(Chapter::class)->findBy([
            'learningModule' => $category->getLearningModule()
        ]);

        $questionCount = [];
        foreach ($topics AS $topic) {
            $questionCount[$topic->getId()] = $this->countQuestions($topic->getId(), $language->getId());
        }



        return $this->render('category/index.html.twig', [
            'category' => $category,
            'topics' => $topics,
            'language' => $language,
            'questionCount' => $questionCount,
        ]);

    }


    private function countQuestions ($topics, $language)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nb', 'totalQuestions');
        $query = $this->getDoctrine()->getManager()->createNativeQuery('SELECT COUNT(id) as nb FROM question WHERE chapter_id = :chapter_id AND language_id= :language_id', $rsm);
        $query->setParameters([
            'language_id' => $language,
            'chapter_id' => $topics
        ]);

        return $query->getSingleScalarResult();
    }


}
