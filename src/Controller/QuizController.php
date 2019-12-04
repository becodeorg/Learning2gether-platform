<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Domain\ChapterManager;
use App\Entity\Quiz;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz/{quiz}", name="quiz")
     */
    public function index(Quiz $quiz)
    {
        return $this->render('quiz/index.html.twig', [
            'quiz' => $quiz
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
}
