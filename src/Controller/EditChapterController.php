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
     * @Route("/partner/edit/module/{module}/chapter/{chapter}", name="edit_chapter", requirements={
     *     "module" = "\d+",
     *     "chapter" = "\d+"
     * })
     * @param Request $request
     * @param LearningModule $module
     * @param Chapter $chapter
     * @return Response
     */
    public function index(Request $request, LearningModule $module, Chapter $chapter): Response
    {
        // Create translation form
        $form = $this->createForm(EditChapterType::class, $chapter);
        $form->handleRequest($request);

        // make a new chapterPage for the form to generate
        $newChapterPage = $chapter->createNewPage();

        // Create form (button) to add a new page
        $createPageBtn = $this->createForm(CreateChapterPageType::class, $newChapterPage);
        $createPageBtn->handleRequest($request);

        // check if the button was pressed
        if ($createPageBtn->isSubmitted() && $createPageBtn->isValid()) {
            $this->createAndAddNewPage($newChapterPage, $chapter);
            $this->flushUpdatedChapter($chapter);
            return $this->redirectToRoute('edit_page', ['module' => $module->getId(), 'chapter' => $chapter->getId(), 'page' => $newChapterPage->getId()]);
        }

        // check if the form was submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedChapter = $form->getData();
            $this->flushUpdatedChapter($updatedChapter);
            return $this->redirectToRoute('edit_module', ['module' => $module->getId()]);
        }

        return $this->render('edit_chapter/index.html.twig', [
            'module' => $module,
            'chapter' => $chapter,
            'form' => $form->createView(),
            'createPage' => $createPageBtn->createView(),
        ]);
    }

    /**
     * @param ChapterPage $newChapterPage
     * @param Chapter|null $chapter
     */
    public function createAndAddNewPage(ChapterPage $newChapterPage, Chapter $chapter): void
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        foreach ($languageAll as $language) {
            $chapterPageTranslation = new ChapterPageTranslation($language, $newChapterPage);
            $newChapterPage->addTranslation($chapterPageTranslation);
        }
        $chapter->addPage($newChapterPage);
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
}
