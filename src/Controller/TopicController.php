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
     * @Route("/forum/category/{category}/topic/{chapter}", name="topic", requirements={
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     })
     */
    public function index(Category $category, Chapter $chapter)
    {

        $languageDummy = $this->getDoctrine()->getRepository(Language::class)->find('1');
        //hard coded out of scope of current ticket
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->find($category);
        $categoryId = $categoryRepo->getId();
        $categoryTitle = $categoryRepo->getLearningModule()->getTitle($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $categoryDescription = $categoryRepo->getLearningModule()->getDescription($this->getDoctrine()->getRepository(Language::class)->find('1'));
        $topics = $this->getDoctrine()->getRepository(Chapter::class)->findOneBy(['id' => $chapter]);

        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(['chapter' => $chapter]);

        $addQuestion = $this->createForm(
            QuestionType::class, [
            'subjectTopic' => '',
            'language' => "",
            'category' => "",
        ], [
                'action' => $this->generateUrl('addQuestion', ['category' => $category->getId(), 'chapter'=> $chapter->getId()])
            ]
        )->createView();


        return $this->render('topic/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categoryTitle' => $categoryTitle,
            'categoryDescription' => $categoryDescription,
              'categoryId' => $category,
             'topicId' => $chapter,
            'topics' => $topics,
            'language' => $languageDummy,
            'questions' => $questions,
            'addQuestion' => $addQuestion,
        ]);
    }



    /**
     * @Route("/category/category/{category}/topic/{chapter}/addQuestion", name="addQuestion" , requirements={
     *     "category"="\d+",
     *     "chapter"="\d+",
     *     })
     */
    public function addQuestion (Request $request, Category $category, Chapter $chapter)
    {

        $form = $this->createForm(QuestionType::class);
        $form->handleRequest($request);

        //I hard coded this because we are still updating the category...
        $categoryCurrent = $this->getDoctrine()->getRepository(Category::class)->find($category->getId());
        $language = $this->getDoctrine()->getRepository(Language::class)->find('1');

        $questionOut = new Question($form->get('subjectTopic')->getData(),$language, $this->getUser(), $categoryCurrent, $chapter);
        $questionOut->setCategory($categoryCurrent);
        $this->getDoctrine()->getManager()->persist($questionOut);
        $this->getDoctrine()->getManager()->flush();
        //todo need to change parameters
        return $this->redirectToRoute('topic', ['category' => $category->getId(), 'chapter'=> $chapter->getId()]);
    }

}
