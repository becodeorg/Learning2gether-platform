<?php

namespace App\Controller;

use App\Entity\ChapterTranslation;
use App\Entity\Language;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\User;
use App\Form\QuizType;
use App\Repository\ChapterRepository;
use App\Repository\ChapterTranslationRepository;
use App\Repository\LearningModuleRepository;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/partner/", name="quiz_index", methods={"GET"})
     */
    public function index(LearningModuleRepository $learningModuleRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'learning_modules' => $learningModuleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/partner/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz, ChapterRepository $chapterRepository): Response
    {
        return $this->render('quiz/show.html.twig', [
            'chapter' => $chapterRepository->findOneBy(['quiz'=> $quiz->getId()]),
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/partner/{id}/edit", name="quiz_edit", methods={"GET","POST"})
     */
    /*public function edit(Request $request, Quiz $quiz, ChapterRepository $chapterRepository): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully edited');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_index');
        }

        return $this->render('quiz/edit.html.twig', [
            'chapter' => $chapterRepository->findOneBy(['quiz'=>$quiz->getId()]),
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }*/

    /*****************
     * We do not want anyone to manually add or edit or delete a Quiz, just show existing ones
     *****************/
}
