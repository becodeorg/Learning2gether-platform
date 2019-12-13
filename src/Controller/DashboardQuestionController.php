<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Domain\LanguageTrait;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\QuizQuestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardQuestionController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("partner/dashboard/chapter/{chapter}/question/{question}", name="dashboard_question", requirements={"chapter"= "\d+", "question"= "\d+"})
     * @param Request $request
     * @param Chapter $chapter
     * @param QuizQuestion $question
     * @return Response
     */
    public function index(Request $request, Chapter $chapter, QuizQuestion $question): Response
    {
        $language = $this->getLanguage($request);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();
        $questionArray = $this->getDoctrine()->getRepository(QuizQuestion::class)->getQuestionsAsArray($question);
        $quizArray = $this->getDoctrine()->getRepository(Chapter::class)->getQuizAsArray($chapter);

        if (!isset($quizArray[0]) && !isset($quizArray[0]['quiz'])){
            $this->addFlash('error', 'Question does not have a quiz attached to it');
            return $this->redirectToRoute('dashboard_chapter', ['chapter' => $chapter->getId()]);
        }

        $fm = new FlaggingManager($languageCount);
        $quizFlags = $fm->checkQuizTranslationSolo($quizArray[0]);

        return $this->render('dashboard_question/index.html.twig', [
            'question' => $question,
            'questionArray' => $questionArray[0],
            'quizArray' => $quizArray[0],
            'language' => $language,
            'languagecount' => $languageCount,
            'quizFlags' => $quizFlags,
        ]);
    }
}
