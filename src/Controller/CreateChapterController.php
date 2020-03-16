<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Form\EditChapterTranslationsType;
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
        // get the english chapter translation from the db
        $english = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
        $englishTranslation = $chapter->getTranslations()->filter(static function ($entry) use ($english) {
            return in_array($entry->getLanguage()->getCode(), (array) $english->getCode(), true);
        });

        $chapterTranslationForm = $this->createForm(EditChapterTranslationsType::class, $englishTranslation[0]);
        $chapterTranslationForm->handleRequest($request);

        if ($chapterTranslationForm->isSubmitted() && $chapterTranslationForm->isValid()) {
            $chapterTranslation = $chapterTranslationForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapterTranslation);
            $em->flush();
            $this->addFlash('success', 'chapter translation saved!');
        }

        return $this->render('create_chapter/index.html.twig', [
            'chapterTranslationForm' => $chapterTranslationForm->createView(),
            'english' => $english,
            'module' => $module,
            'chapter' => $chapter,
        ]);
    }
}
