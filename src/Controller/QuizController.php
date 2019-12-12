<?php

namespace App\Controller;

use App\Domain\LanguageTrait;
use App\Domain\PageManager;
use App\Domain\QuizManager;
use App\Entity\Language;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Entity\QuizQuestion;
use App\Entity\User;
use App\Form\QuizType;
use App\Repository\ChapterRepository;
use App\Repository\LearningModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Badgr;
use App\Domain\ChapterManager;
use Symfony\Component\HttpFoundation\JsonResponse;


class QuizController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("/partner/quiz/", name="quiz_index", methods={"GET"})
     */
   /* public function index(LearningModuleRepository $learningModuleRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'learning_modules' => $learningModuleRepository->findAll(),
        ]);
    }*/

    /**
     * @Route("/partner/quiz/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Request $request, Quiz $quiz, ChapterRepository $chapterRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->query->get('lang') === null){
            $language = $em->getRepository(Language::class)->findOneBy(['code'=>$request->getLocale()]);
            $request->query->set('lang', $language->getCode());
        }else {
            $language = $em->getRepository(Language::class)->findOneBy(['code' => $request->query->get('lang')]);
        }

        return $this->render('quiz/show.html.twig', [
            'chapter' => $chapterRepository->findOneBy(['quiz' => $quiz->getId()]),
            'quiz' => $quiz,
            'language'=>$language,
        ]);
    }

    ///
    ///  FRONTEND QUIZ
    ///

    /**
     * @Route("/portal/quiz/{quiz}", name="quiz_show_user")
     */
    public function showUserQuiz(Request $request, Quiz $quiz)
    {
        /** @var User $user */
        $user = $this->getUser();

        $chapterManager = new ChapterManager($quiz->getChapter());
        $module = $quiz->getChapter()->getLearningModule();

        if ($chapterManager->previous() !== null && !isset($user->getProgressByLearningModule($module)[$chapterManager->previous()->getId()])) {
            $this->addFlash('error', 'You did not unlock the chapter to access this quiz.');
            return $this->redirectToRoute('module', ['module' => $module]);
        }

        return $this->render('quiz/show-user-quiz.html.twig', [
            'quiz' => $quiz,
            'language' => $this->getLanguage($request),
            'module' => $module
        ]);
    }

    /**
     * @Route("/portal/quiz/{quiz}/send", name="quiz_send")
     */
    public function sentQuiz(Quiz $quiz): JsonResponse
    {
        if(!isset($_POST['questions'])) {
            throw new BadRequestHttpException('Missing questions in POST');
        }

        $quizManager = new QuizManager($quiz, $_POST['questions']);

        if ($quizManager->getStatus() === $quizManager::FAIL) {
            return new JsonResponse([
                'status' => $quizManager::FAIL,
                'route' => $this->generateUrl('portal')
            ]);
        }

        $this->getUser()->addProgress($quiz->getChapter());
        $this->getDoctrine()->getManager()->flush();

        if ($quizManager->getStatus() === $quizManager::FINISHED_CHAPTER) {
            $badgrManager = new Badgr;
            $badgrManager->addBadgeToUser(
                $quiz->getChapter()->getLearningModule(),
                $this->getUser()
            );

            //we need to save because we added the Badgr badge to the user in the line above
            $this->getDoctrine()->getManager()->flush();
            $chapterManager = new ChapterManager($quiz->getChapter());

            /* In case the next chapter does not have any pages,
             * point the user to the portal page.
             * This should normally not happen!
             */
            $route = $this->generateUrl('portal');
            if (count($chapterManager->next()->getPages())) {
                $route = $this->generateUrl('module_view_page', [
                    'chapterPage' => $chapterManager->next()->getPages()[0]->getId()//firstPageOfNextChapter
                ]);
            }

            return new JsonResponse([
                'status' => $quizManager::FINISHED_CHAPTER,
                'route'  => $route
            ]);
        }

        return new JsonResponse([
            'status' => $quizManager::FINISHED_CHAPTER,
            'route'  => $this->generateUrl('portal')
        ]);
    }
}
