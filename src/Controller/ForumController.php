<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\UpvoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{

    /**
     * @Route("/forum/topic/{topic}", name="forum", requirements={"topic"="\d+"})
     */
    public function index(Topic $topic)
    {
        $resultsFromPost = "";
        $resultsFromTopic = "";

        //hard coded out of scope of current ticket
        $categoryID = $this->getDoctrine()->getRepository(Category::class)->find('1')->getId();

        $category = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('1')->getTitle();
        //setting up the topic (hard coded)
        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find('1');
        $language = $this->getDoctrine()->getRepository(Language::class)->find('1');

        //display all topics
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();

        //display the current topic by Getter
        //$topic = $this->getDoctrine()->getRepository(Topic::class)->find($_GET['topic_id']);
        $topicDate = $topic->getDate()->format('Y-m-d H:i:s');;

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

        $upvoteForms = [];
        foreach ($posts AS $post) {
            $upvoteForms[$post->getId()] = $this->createForm(
                UpvoteType::class, [
                    'post_id' => $post->getId()
                ],[
                    'action' => $this->generateUrl('upvote'),
                ]
            )->createView();
        }

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
                ->setParameter('keyword', '%' . $_POST['form']['keywords'] . '%')
                ->getQuery();
            $resultsFromPost = $query->getResult();

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository(Topic::class);
            $query = $repo->createQueryBuilder('p')
                ->where('p.subject LIKE :keyword')
                ->setParameter('keyword', '%' . $_POST['form']['keywords'] . '%')
                ->getQuery();
            $resultsFromTopic = $query->getResult();
        }

        //logic for a topic
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form']['subjectTopic'])) {
            $topicOut = new Topic($_POST['form']['subjectTopic'], $language, $this->getUser(), $categoryCurrent);
            $topicOut->setCategory($categoryCurrent);
            $this->getDoctrine()->getManager()->persist($topicOut);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('forum', ['topic' => $post->getTopic()->getId()]);
        }
        // logic for a post
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form']['subjectPost'])) {
            $postOut = new Post($_POST['form']['subjectPost'], $this->getUser(), $topic);
            $postOut->setTopic($topic);
            $this->getDoctrine()->getManager()->persist($postOut);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('forum', ['topic' => $topic->getId()]);
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
            'upvotes' => $upvoteForms,
            'searchbar' => $searchbar->createView(),
            'resultsFromPost' => $resultsFromPost,
            'resultsFromTopic' => $resultsFromTopic,


        ]);
    }


    /**
     * @Route("/forum/upvote", name="upvote")
     */
    public function upvote(Request $request)
    {
        $form = $this->createForm(UpvoteType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('forum', ['topic' => $post->getTopic()->getId()]);
        }

        /** @var Post $post */
        $post = $this->getDoctrine()->getManager()->getRepository(Post::Class)->findOneBy(['id' => $form->get('post_id')->getData()]);

        if ($post === null) {
            $this->addFlash('error', 'This post does not exist!');
            return $this->redirectToRoute('forum', ['topic' => $post->getTopic()->getId()]);
        }

        if ($post->getUsers()->contains($this->getUser())) {
            $this->addFlash('error', 'You already voted!');
        } else {
            $post->addUser($this->getUser());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Your vote was registered!');
        }

        return $this->redirectToRoute('forum', ['topic' => $post->getTopic()->getId()]);
    }
}
