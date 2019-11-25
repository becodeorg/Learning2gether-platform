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
     * @Route("/", name="quiz_index", methods={"GET"})
     */
    public function index(ChapterRepository $chapterRepository): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $chaptTranslationRepo = $em->getRepository(ChapterTranslation::class);
        $titles = [];

        //TODO: remove this IF or put it at a higher level perhaps
        //TODO: also, we need user verification
        //if for example you're not logged in, this will allow you to still test
        //////
        if (!isset($user)){
            $user = $em->getRepository(User::class)->find(1);
            $user->setUsername('Anonymous User');
            $language = $em->getRepository(Language::class)->find(1); //FIXME remove me when needed
            $user->setLanguage($language); //FIXME remove me when needed
        }
        //////

        $language = $user->getLanguage()->getId();

        foreach ($chapterRepository->findAll() as $chapter){
             $chId = $chapter->getId();
             $check = $chaptTranslationRepo->findOneBy(['language'=>$language, 'chapter' => $chId]);
             if ($check != null) {
                 $title = $chaptTranslationRepo->findOneBy(['language' => $language, 'chapter' => $chId])->getTitle();
                 $titles[] = $title;
             }
             else $titles[]='No Title Yet';
        }

        return $this->render('quiz/index.html.twig', [
            //'quizzes' => $quizRepository->findAll(),
            'chapters' => $chapterRepository->findAll(),
            'translated_titles' => $titles,
            'user' => $user
        ]);
    }

//We do not want anyone to manually add a new Quiz, just edit existing ones

    /**
     * @Route("/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz, ChapterRepository $chapterRepository): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        //TODO: remove this IF or put it at a higher level perhaps
        //TODO: also, we need user verification
        //if for example you're not logged in, this will allow you to still test
        //////
        if (!isset($user)){
            $user = $em->getRepository(User::class)->find(1);
            $user->setUsername('Anonymous User');
            $language = $em->getRepository(Language::class)->find(1); //FIXME remove me when needed
            $user->setLanguage($language); //FIXME remove me when needed
        }
        //////
        $language = $user->getLanguage()->getId();

        $questions = $quiz->getQuizQuestions();


        return $this->render('quiz/show.html.twig', [
            'chapter' => $chapterRepository->findOneBy(['quiz'=> $quiz->getId()]),
            'quiz' => $quiz,
            'questions' => $questions,
            'languages' => $em->getRepository(Language::class)->findAll(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quiz $quiz): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_index');
        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

//We do not want anyone to manually delete a Quiz, just edit existing ones

}
