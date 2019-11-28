<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Question;
use App\Form\PostType;
use App\Form\UpvoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chapter;
use Doctrine\ORM\Query\ResultSetMapping;

class QuestionController extends AbstractController
{
    /**
     * @Route("/forum/{category}/{chapter}/{question}", name="question", requirements={
     *
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     "question"="\d+"
     *
     *     })
     */
    public function index(Category $category, Chapter $chapter, Question $question)
    {

        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(['topic' => $question]);

        $upvoters = [];
        $upvoteForms = [];
        foreach ($posts AS $post) {
            $upvoters[$post->getId()] = $this->countVotes($post->getId());
            $upvoteForms[$post->getId()] = $this->createForm(
                UpvoteType::class, [
                'post_id' => $post->getId()
            ],[
                    'action' => $this->generateUrl('upvote',
                        [
                            'category' => $category->getId(),
                            'chapter'=> $chapter->getId(),
                            'question'=> $question->getId()
                        ]),
                ]
            )->createView();
        }

        $postForm = $this->createForm(
            PostType::class, [
            'subjectPost' => '',
            'topic_id' => $question->getId(),
        ], [
                'action' => $this->generateUrl('post',
                    [
                        'category' => $category->getId(),
                        'chapter'=> $chapter->getId(),
                        'question'=> $question->getId()
                    ])
            ]
        )->createView();

        return $this->render('question/index.html.twig', [
            'question' => $question,
            'posts' => $posts,
            'upvotes' => $upvoteForms,
            'upvoters' => $upvoters,
            'postForm' => $postForm,
        ]);
    }

    /**
     * @Route("/forum/{category}/{chapter}/{question}/upvote", name="upvote", requirements={
     *
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     "question"="\d+"
     *
     *     })
     */
    public function upvote(Request $request, Category $category, Chapter $chapter, Question $question)
    {
        $form = $this->createForm(UpvoteType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
        }

        /** @var Post $post */
        $post = $this->getDoctrine()->getManager()->getRepository(Post::Class)->findOneBy(['id' => $form->get('post_id')->getData()]);

        if ($post === null) {
            $this->addFlash('error', 'This post does not exist!');
            return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
        }

        if ($post->getUsers()->contains($this->getUser())) {
            $this->addFlash('error', 'You already voted!');
            return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
        } else {
            $post->addUser($this->getUser());
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Your vote was registered!');
        }

        return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
    }

    /**
     * @Route("/forum/{category}/{chapter}/{question}/post", name="post", requirements={
     *
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     "question"="\d+"
     *
     *     })
     */
    public function post (Request $request, Category $category, Chapter $chapter, Question $question)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        /** @var Question $topic */
        $topic = $this->getDoctrine()->getManager()->getRepository(Question::Class)->findOneBy(['id' => $form->get('topic_id')->getData()]);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
        }


        $postOut = new Post($form->get('subjectPost')->getData(), $this->getUser(), $topic);

        $postOut->setTopic($topic);

        $this->getDoctrine()->getManager()->persist($postOut);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $question->getId()]);
    }

    private function countVotes ($post)
    {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('nb', 'totalupvotes');
        $query = $this->getDoctrine()->getManager()->createNativeQuery('SELECT COUNT(post_id) as nb FROM user_post WHERE post_id = :post_id', $rsm);
        $query->setParameters([
            'post_id' => $post
        ]);

        $upvotes = $query->getSingleScalarResult();
        return $upvotes;
    }

}
