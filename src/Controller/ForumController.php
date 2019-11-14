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

class ForumController extends AbstractController
{

    /**
     * @Route("/forum/category/{category}", name="forum", requirements={"category"="\d+"})
     */
    public function index(Category $category)
    {
        $languageDummy = $this->getDoctrine()->getRepository(Language::class)->find('1');
        //hard coded out of scope of current ticket
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->find($category);

        $categoryTitle = $categoryRepo->getLearningModule()->getTitle($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $categoryDescription = $categoryRepo->getLearningModule()->getDescription($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $chapters = $this->getDoctrine()->getRepository(Chapter::class)->findBy(['learningModule' => $categoryRepo->getId()]);

        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

        $addQuestion = $this->createForm(
            QuestionType::class, [
            'subjectTopic' => '',
            'language' => "",
            'category' => "",
        ], [
                'action' => $this->generateUrl('addQuestion')
            ]
        )->createView();

        $searchbar = $this->createForm(
            SearchbarType::class, [
                'search' => ''
            ], [
                'action' => $this->generateUrl('searchbar')
            ]
        )->createView();

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'categoryTitle' => $categoryTitle,
            'categoryDescription' => $categoryDescription,
            'chapters' => $chapters,
            'language' => $languageDummy,
            'questions' => $questions,
            'addQuestion' => $addQuestion,
            'searchbar' => $searchbar,
        ]);
    }

    /**
     * @Route("/forum/searchbar", name="searchbar")
     */
    public function searchbar(Request $request)
    {
        $form = $this->createForm(SearchbarType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        $query = $repo->createQueryBuilder('p')
            ->where('p.subject LIKE :keyword')
            ->setParameter('keyword', '%' . $form->get('keywords')->getData() . '%')
            ->getQuery();
        $resultsFromPost = $query->getResult();

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Question::class);
        $query = $repo->createQueryBuilder('p')
            ->where('p.subject LIKE :keyword')
            ->setParameter('keyword', '%' . $form->get('keywords')->getData() . '%')
            ->getQuery();
        $resultsFromQuestion = $query->getResult();

        return $this->render('forum/searchResult.html.twig', [
            'controller_name' => 'ForumController',
            'resultsFromPost' => $resultsFromPost,
            'resultsFromQuestion' => $resultsFromQuestion,

        ]);
    }

    /**
     * @Route("/forum/addQuestion", name="addQuestion")
     */
    public function addQuestion (Request $request)
    {

        $form = $this->createForm(QuestionType::class);
        $form->handleRequest($request);

        //I hard coded this because we are still updating the forum...
        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find('1');
        $language = $this->getDoctrine()->getRepository(Language::class)->find('1');

        $questionOut = new Question($form->get('subjectTopic')->getData(),$language, $this->getUser(), $categoryCurrent);
        $questionOut->setCategory($categoryCurrent);
        $this->getDoctrine()->getManager()->persist($questionOut);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('forum');
    }

}
