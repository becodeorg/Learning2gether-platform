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

/**
 * @Route("/quiz/question")
 */
class QuizQuestionController extends AbstractController
{



    /**
     * @Route("/", name="quiz_question_index", methods={"GET"})
     */
    public function index(QuizQuestionRepository $quizQuestionRepository): Response
    {
        return $this->render('quiz_question/index.html.twig', [
            'quiz_questions' => $quizQuestionRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="quiz_question_new", methods={"GET","POST"}, requirements={
     *     "id" = "\d+"})
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository(Quiz::class)->find($id);
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);

        $repo = $em->getRepository(QuizQuestion::class);
        $questionNmbr = $repo->createQueryBuilder('q')
            ->andWhere('q.quiz = :id')
            ->setParameter('id', $id)
            ->select('count(q)')
            ->getQuery()
            ->getSingleScalarResult(); //returns the int value of the count

        $quizQuestion = new QuizQuestion(++$questionNmbr, $quiz);
        $quizQuestionTranslation = new QuizQuestionTranslation($language, '', $quizQuestion);

        $form = $this->createForm(QuizQuestionTranslationType::class, $quizQuestionTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}", name="quiz_question_show", methods={"GET"}, requirements={
     *     "id" = "\d+"})
     */
    public function show(QuizQuestionRepository $quizQuestion, int $id): Response
    {
        return $this->render('quiz_question/show.html.twig', [
            'quiz_question' => $quizQuestion->find($id),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizQuestion $quizQuestion): Response
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
        $qqTs= $quizQuestion->getTranslations();

        foreach ($qqTs as $qqT){
            if ($qqT->getLanguage()->getCode() === $language->getCode()){
                $quizQuestionTrans = $qqT;
                $questionNumber = $qqT->getQuizQuestion()->getQuestionNumber();
            }
        }

        $form = $this->createForm(QuizQuestionTranslationType::class, $quizQuestionTrans);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('quiz_question_index');
        }

        return $this->render('quiz_question/edit.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="quiz_question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuizQuestion $quizQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_show', ['id'=>$quizQuestion->getQuiz()->getId()]);
    }
}
