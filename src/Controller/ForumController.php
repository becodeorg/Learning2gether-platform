<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Tests\Fixtures\FooBundle\Controller\OptionalArgumentsController;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {
        $resultsFromPost = "";
        $resultsFromTopic = "";
        if (!isset($_GET['topic_id'])) {
            $_GET['topic_id'] = 1;
        }

        //hard coded out of scope of current ticket
        $categoryID = $this->getDoctrine()->getRepository(Category::class)->find('1')->getId();

        $category = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('1')->getTitle();
        //setting up the topic (hard coded)
        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find('1');
        $language = $this->getDoctrine()->getRepository(Language::class)->find('1');

        //display all topics
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();

        //display the current topic by Getter
        $topic = $this->getDoctrine()->getRepository(Topic::class)->find($_GET['topic_id']);
        $topicDate = $this->getDoctrine()->getRepository(Topic::class)->find($_GET['topic_id'])->getDate()->format('Y-m-d H:i:s');;

        //small form to post topic
        $topicNow = $this->createFormBuilder()
            ->add('subjectTopic', TextType::class)
            ->add('postTopic', SubmitType::class, array('label' => 'Add Topic'))
            ->getForm();

        //display posts that are needed to be displayed
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(['topic' => $topic->getId()]);

        //reply to a topic here with Post
        $postNow = $this->createFormBuilder()
            ->add('subjectPost', TextType::class)
            ->add('postPost', SubmitType::class, array('label' => 'Post'))
            ->getForm();

        //fixme problem with twig inplementation for multiuse
        $upvote = $this->createFormBuilder()
            ->add('upvote', SubmitType::class, array('label' => 'Upvote'))
            ->getForm();

        //searchbar
        $searchbar = $this->createFormBuilder()
            ->add('keywords', TextType::class)
            ->add('search', SubmitType::class, array('label' => 'Search Now'))
            ->getForm();

        //logic for searchbar
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form']['search'])) {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Post::class);
            $query = $repo->createQueryBuilder('p')
                ->where('p.subject LIKE :keyword')
                ->setParameter('keyword', '%'.$_POST['form']['keywords'].'%')
                ->getQuery();
            $resultsFromPost = $query->getResult();

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Topic::class);
            $query = $repo->createQueryBuilder('p')
                ->where('p.subject LIKE :keyword')
                ->setParameter('keyword', '%'.$_POST['form']['keywords'].'%')
                ->getQuery();
            $resultsFromTopic = $query->getResult();
        }

        //logic for a topic
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form']['subjectTopic'])) {
             $topicOut = new Topic($_POST['form']['subjectTopic'], $language, $this->getUser(), $categoryCurrent);
            $topicOut->setCategory($categoryCurrent);
            $this->getDoctrine()->getManager()->persist($topicOut);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('forum', ['topic_id' => $topic->getId()]);
        }
        // logic for a post
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form']['subjectPost'])) {
             $postOut = new Post($_POST['form']['subjectPost'], $this->getUser(), $topic);
             $postOut->setTopic($topic);
             $this->getDoctrine()->getManager()->persist($postOut);
             $this->getDoctrine()->getManager()->flush();
             return $this->redirectToRoute('forum', ['topic_id' => $topic->getId()]);
        }

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'categoryID' => $categoryID,
            'category' => $category,
            'topics' => $topics,
            'topic' => $topic->getSubject(),
            'topic_date' => $topicDate,
            'posts' => $posts,
            'post_now' => $postNow->createView(),
            'topic_now' => $topicNow->createView(),
            'upvote' => $upvote->createView(),
            'searchbar' => $searchbar->createView(),
            'resultsFromPost' => $resultsFromPost,
            'resultsFromTopic' => $resultsFromTopic,

        ]);
    }
}
