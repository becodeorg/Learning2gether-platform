<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Entity\Chapter;
use App\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardChapterController extends AbstractController
{
    /**
     * @Route("partner/dashboard/chapter/{chapter}", name="dashboard_chapter", requirements={"chapter"= "\d+"})
     * @param Chapter $chapter
     * @return Response
     */
    public function index(Chapter $chapter): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();
        $chapterArray = $this->getDoctrine()->getRepository(Chapter::class)->getChapterAsArray($chapter);

        $fm = new FlaggingManager();
        $pagesFlags = $fm->checkChapter($chapterArray, $languageCount);

        return $this->render('dashboard_chapter/index.html.twig', [
            'fm' => $fm,
            'chapter' => $chapter,
            'language' => $language,
            'languagecount' => $languageCount,
            'chapterArray' => $chapterArray[0],
            'pagesFlags' => $pagesFlags['pages'],
        ]);
    }
}
