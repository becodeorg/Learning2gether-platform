<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\QuizAnswer;
use App\Entity\QuizAnswerTranslation;
use App\Entity\QuizQuestion;
use App\Form\QuizAnswerTranslationType;
use App\Form\QuizAnswerType;
use App\Repository\QuizAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz/answer")
 */
class QuizAnswerController extends AbstractController
{
    /**
     * @Route("/", name="quiz_answer_index", methods={"GET"})
     */
    public function index(QuizAnswerRepository $quizAnswerRepository): Response
    {
        return $this->render('quiz_answer/index.html.twig', [
            'quiz_answers' => $quizAnswerRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="quiz_answer_new", methods={"GET","POST"}, requirements={
     *     "id" = "\d+"})
     */
    public function new(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $quizQuestion = $em->getRepository(QuizQuestion::class)->find($id);
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);


        $quizAnswer = new QuizAnswer(false, $quizQuestion);
        $quizAnswerTranslation = new QuizAnswerTranslation($language, '', $quizAnswer);

        $form = $this->createForm(QuizAnswerTranslationType::class, $quizAnswerTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quizAnswerTranslation = $form->getData();
            $quizAnswer->addTranslation($quizAnswerTranslation);
            $em->persist($quizAnswer);
            $em->flush();

            return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
        }

        return $this->render('quiz_answer/new.html.twig', [
            'quiz_answer' => $quizAnswer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_answer_show", methods={"GET"})
     */
    public function show(QuizAnswer $quizAnswer): Response
    {
        return $this->render('quiz_answer/show.html.twig', [
            'quiz_answer' => $quizAnswer,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_answer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizAnswer $quizAnswer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
        $qaTs= $quizAnswer->getTranslations();

        foreach ($qaTs as $qaT){
            if ($qaT->getLanguage()->getCode() === $language->getCode()){
                $quizAnswerTrans = $qaT;
                $questionNumber = $qaT->getQuizAnswer()->getQuizQuestion()->getQuestionNumber();
            }
        }

        $form = $this->createForm(QuizAnswerTranslationType::class, $quizAnswerTrans);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('quiz_show', ['id'=>$quizAnswer->getQuizQuestion()->getQuiz()->getId()]);
        }

        return $this->render('quiz_answer/edit.html.twig', [
            'quiz_answer' => $quizAnswer,
            'quiz_answer_trans' => $quizAnswerTrans,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_answer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuizAnswer $quizAnswer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizAnswer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizAnswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_show', ['id'=>$quizAnswer->getQuizQuestion()->getQuiz()->getId()]);
    }
}
