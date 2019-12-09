<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterTranslation;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Form\CreateChapterPageType;
use App\Form\EditChapterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateChapterController extends AbstractController
{
    /**
     * @Route("partner/create/module/{module}/chapter/{chapter}", name="create_chapter", requirements={"module"="\d+", "chapter"="\d+"})
     * @param LearningModule $module
     * @param Chapter $chapter
     * @param Request $request
     * @return Response
     */
    public function index(LearningModule $module, Chapter $chapter, Request $request): Response
    {
        $english = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
        $chapterTranslation = new ChapterTranslation($english, $chapter);
        $chapter->addTranslation($chapterTranslation);
        $chapterTranslationForm = $this->createForm(EditChapterType::class, $chapter);
        $chapterTranslationForm->handleRequest($request);

        // TODO add empty chapter translations

        $page = $chapter->createNewPage();
        $addPageBtn = $this->createForm(CreateChapterPageType::class, $page);
        $addPageBtn->handleRequest($request);

        if ($chapterTranslationForm->isSubmitted() && $chapterTranslationForm->isValid()){
            $chapter = $chapterTranslationForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapter);
            $em->flush();
        }

        if ($addPageBtn->isSubmitted() && $addPageBtn->isValid()){
            return $this->redirectToRoute('create_page', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
        }

        return $this->render('create_chapter/index.html.twig', [
            'chapterTranslationForm' => $chapterTranslationForm->createView(),
            'addPageBtn' => $addPageBtn->createView(),
            'english' => $english,
            'module' => $module,
            'chapter' => $chapter,
        ]);
    }
}
