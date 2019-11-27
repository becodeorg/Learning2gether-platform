<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Form\SearchbarType;
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
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->find($category);
        $currentTopic = $this->getDoctrine()->getRepository(Chapter::class)->findOneBy(['id' => $chapter]);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy([
            'chapter' => $chapter,
            'language'=>  $language->getId()
        ]);

        $addQuestion = $this->createForm(
            QuestionType::class, [
            'subjectTopic' => '',
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
            'category' => $categoryRepo,
            'currentTopic' => $currentTopic,
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

        $questionOut = new Question($form->get('subjectTopic')->getData(),$language, $this->getUser(), $categoryCurrent, $chapter);

        $this->getDoctrine()->getManager()->persist($questionOut);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('topic', ['category' => $category->getId(), 'chapter'=> $chapter->getId()]);
    }

}