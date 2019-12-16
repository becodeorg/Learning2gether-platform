<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Entity\QuizQuestionTranslation;
use App\Form\QuizQuestionTranslationType;
use App\Form\QuizQuestionType;
use App\Repository\QuizQuestionRepository;
use App\Repository\QuizQuestionTranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuizQuestionController extends AbstractController
{

    /**
     * @Route("/partner/quiz/question/", name="quiz_question_index", methods={"GET"})
     */
    public function index(QuizQuestionRepository $quizQuestionRepository): Response
    {
        return $this->render('quiz_question/index.html.twig', [
            'quiz_questions' => $quizQuestionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/partner/quiz/question/new/{id}", name="quiz_question_new", methods={"GET","POST"}, requirements={
     *     "id" = "\d+"})
     */
    public function new(Request $request, Quiz $quiz): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(QuizQuestion::class);

        if ($request->query->get('lang') === null){
            $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
            $request->query->set('lang', $language->getCode());
        }else {
            $language = $em->getRepository(Language::class)->findOneBy(['code' => $request->query->get('lang')]);
        }

        $questionNmbr = $repo->findNumberOfQuestionsForGivenID($quiz->getId());

        $quizQuestion = new QuizQuestion(++$questionNmbr, $quiz);
        $quizQuestionTranslation = new QuizQuestionTranslation($quizQuestion, $language);

        $form = $this->createForm(QuizQuestionTranslationType::class, $quizQuestionTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully added a new question');
            $quizQuestionTranslation = $form->getData();
            $quizQuestion->addTranslation($quizQuestionTranslation);

            $em->persist($quizQuestion);
            $em->flush();

            return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
        }

        return $this->render('quiz_question/new.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/partner/quiz/question/{id}", name="quiz_question_show", methods={"GET"}, requirements={
     *     "id" = "\d+"})
     */
    public function show(QuizQuestionRepository $quizQuestion, int $id): Response
    {
        return $this->render('quiz_question/show.html.twig', [
            'quiz_question' => $quizQuestion->find($id),
        ]);
    }

    /**
     * @Route("/partner/quiz/question/{id}/edit", name="quiz_question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizQuestion $quizQuestion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $quizQuestionTranslations= $quizQuestion->getTranslations();

        if ($request->query->get('lang') === null){
            $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
            $request->query->set('lang', $language->getCode());
        }else {
            $language = $em->getRepository(Language::class)->findOneBy(['code' => $request->query->get('lang')]);
        }

        foreach ($quizQuestionTranslations as $quizQuestionTrans){
            if ($quizQuestionTrans->getLanguage()->getCode() === $language->getCode()){
                $quizQuestionTranslation = $quizQuestionTrans;
            }
        }

        //if not translation was found, create a new one for the current language, so it can be edited
        if (!isset($quizQuestionTranslation)){
            $quizQuestionTranslation = new QuizQuestionTranslation($quizQuestion, $language, 'undefined');
        }

        $form = $this->createForm(QuizQuestionTranslationType::class, $quizQuestionTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Successfully edited your question');
            $em->persist($quizQuestionTranslation); //persist because we might be creating a new QQTranslation
            $em->flush();

            if (isset($_GET['return'])){
                switch ($_GET['return']){
                    case 'flow':
                        return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
                    case 'dash':
                        return $this->redirectToRoute('dashboard_chapter', ['module' => $quizQuestion->getQuiz()->getChapter()->getLearningModule()->getId(), 'chapter' => $quizQuestion->getQuiz()->getChapter()->getId()]);
                    default:
                        return $this->redirectToRoute('partner');
                }
            }

//            return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
        }

        return $this->render('quiz_question/edit.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/partner/quiz/question/{id}", name="quiz_question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuizQuestion $quizQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Successfully removed your question');

            $entityManager->remove($quizQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
    }
}
