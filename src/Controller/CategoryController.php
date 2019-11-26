<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Question;
use App\Entity\User;
use App\Form\PostType;
use App\Form\SearchbarType;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/forum/category/{category}", name="category", requirements={
     *     "category"="\d+",
     *
     *     })
     */
    public function index(Category $category)
    {

        $languageDummy = $this->getDoctrine()->getRepository(Language::class)->find('1');
        //hard coded out of scope of current ticket
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->find($category);
        $categoryId = $categoryRepo->getId();
        $categoryTitle = $categoryRepo->getLearningModule()->getTitle($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $categoryDescription = $categoryRepo->getLearningModule()->getDescription($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $topics = $this->getDoctrine()->getRepository(Chapter::class)->findBy(['learningModule' => $categoryRepo->getId()]);

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categoryId' => $categoryId,
            'categoryTitle' => $categoryTitle,
            'categoryDescription' => $categoryDescription,
            'topics' => $topics,
            'language' => $languageDummy,
        ]);
    }


}
