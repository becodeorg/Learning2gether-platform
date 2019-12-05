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


class QuizAnswerController extends AbstractController
{
    /**
     * @Route("/partner/quiz/answer", name="quiz_answer_index", methods={"GET"})
     */
    public function index(QuizAnswerRepository $quizAnswerRepository): Response
    {
        return $this->render('quiz_answer/index.html.twig', [
            'quiz_answers' => $quizAnswerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/partner/quiz/answer/new/{id}", name="quiz_answer_new", methods={"GET","POST"}, requirements={
     *     "id" = "\d+"})
     */
    public function new(Request $request, QuizQuestion $quizQuestion): Response
    {
        $em = $this->getDoctrine()->getManager();
        //$quizQuestion = $em->getRepository(QuizQuestion::class)->find($id);
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);


        $quizAnswer = new QuizAnswer($quizQuestion);
        $quizAnswerTranslation = new QuizAnswerTranslation($quizAnswer,$language);

        $form = $this->createForm(QuizAnswerTranslationType::class, $quizAnswerTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully added a new answer');
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
     * @Route("/partner/quiz/answer/{id}", name="quiz_answer_show", methods={"GET"})
     */
    public function show(QuizAnswer $quizAnswer): Response
    {
        return $this->render('quiz_answer/show.html.twig', [
            'quiz_answer' => $quizAnswer,
        ]);
    }

    /**
     * @Route("/partner/quiz/answer/{id}/edit", name="quiz_answer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizAnswer $quizAnswer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
        $quizAnswerTranslations= $quizAnswer->getTranslations();

        foreach ($quizAnswerTranslations as $quizAnswerTrans){
            if ($quizAnswerTrans->getLanguage()->getCode() === $language->getCode()){
                $quizAnswerTranslation = $quizAnswerTrans;
            }
        }

        //if not translation was found, create a new one for the current language, so it can be edited
        if (!isset($quizAnswerTranslation)){
            $quizAnswerTranslation = new QuizAnswerTranslation($quizAnswer, $language, 'undefined');
        }

        $form = $this->createForm(QuizAnswerTranslationType::class, $quizAnswerTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully edited your answer');
            $em->persist($quizAnswerTranslation); //persist because we might be creating a new QATranslation
            $em->flush();

            return $this->redirectToRoute('quiz_show', ['id'=>$quizAnswer->getQuizQuestion()->getQuiz()->getId()]);
        }

        return $this->render('quiz_answer/edit.html.twig', [
            'quiz_answer' => $quizAnswer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/partner/quiz/answer/{id}", name="quiz_answer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuizAnswer $quizAnswer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizAnswer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Successfully removed your answer');

            $entityManager->remove($quizAnswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_show', ['id'=>$quizAnswer->getQuizQuestion()->getQuiz()->getId()]);
    }
}
