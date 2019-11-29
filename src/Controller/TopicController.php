<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    /**
     * @Route("/forum/{category}/{chapter}", name="topic", requirements={
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     })
     */
    public function index(Request $request, Category $category, Chapter $chapter)
    {

        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy([
            'chapter' => $chapter,
            'language'=>  $language->getId()
        ]);

        $addQuestion = $this->createForm(
            QuestionType::class, [
            'question' => '',
            'language' => "",
            'category' => "",
        ], [
                'action' => $this->generateUrl('addQuestion',
                    [
                        'category' => $category->getId(),
                        'chapter'=> $chapter->getId()
                    ])
            ]
        )->createView();

        return $this->render('topic/index.html.twig', [
            'category' => $category,
            'currentTopic' => $chapter,
            'language' => $language,
            'questions' => $questions,
            'addQuestion' => $addQuestion,
        ]);
    }

    /**
     * @Route("/forum/{category}/{chapter}/addQuestion", name="addQuestion" , requirements={
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     })
     */
    public function addQuestion (Request $request, Category $category, Chapter $chapter)
    {

        $form = $this->createForm(QuestionType::class);
        $form->handleRequest($request);

        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find($category->getId());
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);

        $questionOut = new Question($form->get('question')->getData(),$language, $this->getUser(), $categoryCurrent, $chapter);
        $postOut = new Post($form->get('questionDescription')->getData(), $this->getUser(), $questionOut);

        $this->getDoctrine()->getManager()->persist($questionOut);
        $this->getDoctrine()->getManager()->persist($postOut);
        $this->getDoctrine()->getManager()->flush();

        $question = $this->getDoctrine()->getManager()->getRepository(Question::class)->find($questionOut->getId());

        return $this->redirectToRoute('question', ['category' => $category->getId(), 'chapter'=> $chapter->getId(), 'question'=> $questionOut->getId()]);
    }

}
