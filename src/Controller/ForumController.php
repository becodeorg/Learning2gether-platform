<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Question;
use App\Entity\User;
use App\Form\PostType;
use App\Form\SearchbarType;
use App\Form\TopicType;
use App\Form\UpvoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ForumController extends AbstractController
{

    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {

        //hard coded out of scope of current ticket
        $categoryID = $this->getDoctrine()->getRepository(Category::class)->find('1')->getId();
        $category = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('1')->getTitle();

        //display all topics
        $topics = $this->getDoctrine()->getRepository(Question::class)->findAll();

        $topicNow = $this->createForm(
            TopicType::class, [
            'subjectTopic' => '',
            'language' => "",
            'category' => "",
        ], [
                'action' => $this->generateUrl('addTopic')
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
           'categoryID' => $categoryID,
            'category' => $category,
            'topics' => $topics,
            'topic_now' => $topicNow,
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
        $resultsFromTopic = $query->getResult();

        return $this->render('forum/searchResult.html.twig', [
            'controller_name' => 'ForumController',
            'resultsFromPost' => $resultsFromPost,
            'resultsFromTopic' => $resultsFromTopic,

        ]);
    }

    /**
     * @Route("/forum/addTopic", name="addTopic")
     */
    public function addTopic (Request $request)
    {

        $form = $this->createForm(TopicType::class);
        $form->handleRequest($request);

        //I hard coded this because we are still updating the forum...
        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find('1');
        $language = $this->getDoctrine()->getRepository(Language::class)->find('1');


        $topicOut = new Question($form->get('subjectTopic')->getData(),$language, $this->getUser(), $categoryCurrent);
        $topicOut->setCategory($categoryCurrent);
        $this->getDoctrine()->getManager()->persist($topicOut);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('forum');
    }

}
