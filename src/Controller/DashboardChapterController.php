<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Domain\LanguageTrait;
use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardChapterController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("partner/dashboard/chapter/{chapter}", name="dashboard_chapter", requirements={"chapter"= "\d+"})
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function index(Request $request, Chapter $chapter): Response
    {
        $language = $this->getLanguage($request);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();

        $pr = $this->getDoctrine()->getRepository(ChapterPage::class);
        $cr = $this->getDoctrine()->getRepository(Chapter::class);
        $fm = new FlaggingManager($languageCount);

        return $this->render('dashboard_chapter/index.html.twig', [
            'fm' => $fm,
            'chapter' => $chapter,
            'language' => $language,
            'pr' => $pr,
            'cr' => $cr,
            'languagecount' => $languageCount,
        ]);
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("partner/dashboard/chapter/sort/{id}/{position}", name="dashboard_chapter_sort", requirements={"chapter"= "\d+"})
     * @param integer $id
     * @param integer $position
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sortAction($id, $position)
    {
        $em = $this->getDoctrine()->getManager();

        $chapter = $em->getRepository(Chapter::class)->find($id);
        $chapter->setPosition($position);

        $em->persist($chapter);
        $em->flush();

        return new Response(
            'All ok!',
            Response::HTTP_OK
        );
    }
}
