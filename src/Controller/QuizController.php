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
use App\Domain\Badgr;
use App\Domain\ChapterManager;
use Symfony\Component\HttpFoundation\JsonResponse;


class QuizController extends AbstractController
{
    /**
     * @Route("/partner/quiz/", name="quiz_index", methods={"GET"})
     */
    public function index(LearningModuleRepository $learningModuleRepository): Response
    {
        return $this->render('quiz/index.html.twig', [
            'learning_modules' => $learningModuleRepository->findAll(),
        ]);
    }

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
            'chapter' => $chapterRepository->findOneBy(['quiz'=> $quiz->getId()]),
            'quiz' => $quiz,
            'language'=>$language,
        ]);
    }

    /**
     * @Route("/quiz/{quiz}/send", name="quiz_send")
     */
    public function sentQuiz(Quiz $quiz): JsonResponse
    {
        //@todo: tmp code because out of scope of ticket
        //just added this so the frontend people can already try out the API
        $list = ['PASS', 'FAIL', 'MODULE_FINISHED'];
        $index = array_rand($list);

        return new JsonResponse([
            'status' => $list[$index],
            'badge' => 'example.jpg'
        ]);
    }

    /**
     * @Route("/quiz/{quiz}/finished", name="quiz_finished")
     */
    public function quizFinished(Quiz $quiz)
    {
        //@todo: tmp code - this should not be available with a route - but called from sentQuiz

        /** @var User $user */
        $user = $this->getUser();
        $user->addProgress($quiz->getChapter());
        $this->getDoctrine()->getManager()->flush();

        try {
            $chapterManager = new ChapterManager($quiz->getChapter());

            if (!$chapterManager->isLast()) {
                if (!count($chapterManager->next()->getPages())) {
                    throw new \DomainException('The next chapter does not have any pages! Please contact the site administrator.');
                }

                $this->addFlash('success', 'You completed the quiz and unlocked the next chapter!');

                return $this->redirectToRoute('module_view_page', [
                    'chapterPage' => $chapterManager->next()->getPages()[0]->getId()//firstPageOfNextChapter
                ]);
            }

            // when module completed, give badge
            $badgrManager = new Badgr;
            $badgrManager->addBadgeToUser(
                $quiz->getChapter()->getLearningModule(),
                $user
            );
            //we need to save because we added the Badgr badge to the user in the line above
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'You finished the learning module and recieved your badge!');
            return $this->redirectToRoute('portal');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
            $this->redirectToRoute('module', [
                'module' => $quiz->getChapter()->getLearningModule()->getId()
            ]);
        }
    }

    /*****************
     * We do not want anyone to manually add or edit or delete a Quiz, just show existing ones
     *****************/
}
