<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Form\CreateChapterPageType;
use App\Form\EditChapterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditChapterController extends AbstractController
{
    /**
     * @Route("/edit/chapter", name="edit_chapter")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($_GET['module']);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($_GET['chapter']);

        $pageCount = count($chapter->getPages());
        $newChapterPage = new ChapterPage(++$pageCount, $chapter);

        $form = $this->createForm(EditChapterType::class, $chapter);
        $form->handleRequest($request);

        $createPageBtn = $this->createForm(CreateChapterPageType::class, $newChapterPage);
        $createPageBtn->handleRequest($request);

        if ($createPageBtn->isSubmitted() && $createPageBtn->isValid()) {
            $this->createAndAddNewPage($newChapterPage, $chapter);
            $this->flushUpdatedChapter($chapter);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedChapter = $form->getData();
            $this->flushUpdatedChapter($updatedChapter);
        }

        return $this->render('edit_chapter/index.html.twig', [
            'controller_name' => 'EditChapterController',
            'module' => $module,
            'chapter' => $chapter,
            'form' => $form->createView(),
            'createPage' => $createPageBtn->createView(),
        ]);
    }

    /**
     * @param $updatedChapter
     */
    public function flushUpdatedChapter($updatedChapter): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($updatedChapter);
        $em->flush();
    }

    /**
     * @param ChapterPage $newChapterPage
     * @param Chapter|null $chapter
     */
    public function createAndAddNewPage(ChapterPage $newChapterPage, ?Chapter $chapter): void
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        foreach ($languageAll as $language) {
            $chapterPageTranslation = new ChapterPageTranslation($language, $newChapterPage, '');
            $newChapterPage->addTranslation($chapterPageTranslation);
        }
        $chapter->addPage($newChapterPage);
    }
}
