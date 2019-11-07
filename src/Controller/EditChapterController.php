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
        // check the $_GET vars, they have to be set, and integers, if not, redirects back to partner
        if (isset($_GET['module'], $_GET['chapter']) && ctype_digit((string)$_GET['module']) && ctype_digit((string)$_GET['chapter'])) {
            //get this Module
            $moduleID = $_GET['module'];
            $chapterID = $_GET['chapter'];
        } else {
            return $this->redirectToRoute('partner');
        }

        // find the current module and the current chapter
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($chapterID);

        // Create translation form
        $form = $this->createForm(EditChapterType::class, $chapter);
        $form->handleRequest($request);

        // make a new chapterPage for the form to generate
        $pageCount = count($chapter->getPages());
        $newChapterPage = new ChapterPage(++$pageCount, $chapter);

        // Create form (button) to add a new page
        $createPageBtn = $this->createForm(CreateChapterPageType::class, $newChapterPage);
        $createPageBtn->handleRequest($request);

        // check if the button was pressed
        if ($createPageBtn->isSubmitted() && $createPageBtn->isValid()) {
            $this->createAndAddNewPage($newChapterPage, $chapter);
            $this->flushUpdatedChapter($chapter);
            // should it now redirect to the new page ?
        }

        // check if the form was submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedChapter = $form->getData();
            $this->flushUpdatedChapter($updatedChapter);
            return $this->redirectToRoute('edit_module', ['module' => $moduleID]);
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
