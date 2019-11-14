<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Topic;
use App\Form\PostType;
use App\Form\UpvoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @Route("/forum/topic/{topic}", name="topic", requirements={"topic"="\d+"})
     */
    public function index(Topic $topic)
    {

        $topicDate = $topic->getDate()->format('Y-m-d H:i:s');;
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(['topic' => $topic->getId()]);

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

        $postForm = $this->createForm(
            PostType::class, [
            'subjectPost' => '',
            'topic_id' => $topic->getId(),
        ], [
                'action' => $this->generateUrl('post')
            ]
        )->createView();

        return $this->render('topic/index.html.twig', [
            'controller_name' => 'TopicController',
            'topic' => $topic->getSubject(),
            'topic_date' => $topicDate,
            'posts' => $posts,
            'upvotes' => $upvoteForms,
            'postForm' => $postForm,
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
            return $this->redirectToRoute('topic', ['topic' => $post->getTopic()->getId()]);
        }

        /** @var Post $post */
        $post = $this->getDoctrine()->getManager()->getRepository(Post::Class)->findOneBy(['id' => $form->get('post_id')->getData()]);

        if ($post === null) {
            $this->addFlash('error', 'This post does not exist!');
            return $this->redirectToRoute('topic', ['topic' => $post->getTopic()->getId()]);
        }

        if ($post->getUsers()->contains($this->getUser())) {
            $this->addFlash('error', 'You already voted!');
            return $this->redirectToRoute('topic', ['topic' => $post->getTopic()->getId()]);
        } else {
            $post->addUser($this->getUser());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Your vote was registered!');
        }

        return $this->redirectToRoute('topic', ['topic' => $post->getTopic()->getId()]);
    }

    /**
     * @Route("/forum/post", name="post")
     */
    public function post (Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        /** @var Topic $topic */
        $topic = $this->getDoctrine()->getManager()->getRepository(Topic::Class)->findOneBy(['id' => $form->get('topic_id')->getData()]);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('topic', ['topic' => $topic->getId()]);
        }


        $postOut = new Post($form->get('subjectPost')->getData(), $this->getUser(), $topic);

        $postOut->setTopic($topic);

        $this->getDoctrine()->getManager()->persist($postOut);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('topic', ['topic' => $topic->getId()]);
    }
}
