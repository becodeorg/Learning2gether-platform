<?php

namespace App\Controller;

use App\Domain\ImageManager;
use App\Entity\Chapter;
use App\Entity\ChapterTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\Quiz;
use App\Form\CreateChapterType;
use App\Form\EditModuleType;
use App\Form\ImageUploaderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditModuleController extends AbstractController
{
    /**
     * @Route("/partner/edit/module/{module}", name="edit_module", requirements={"module"= "\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return Response
     */
    public function index(Request $request, LearningModule $module): Response
    {
        $imageManager = new ImageManager();

        $user = $this->getUser();

        $form = $this->createForm(EditModuleType::class, $module);
        $form->handleRequest($request);

        $newChapter = new Chapter($module);
        $chapterBtn = $this->createForm(CreateChapterType::class, $newChapter);
        $chapterBtn->handleRequest($request);

        $uploader = $this->createForm(ImageUploaderType::class);
        $uploader->handleRequest($request);

        if ($chapterBtn->isSubmitted() && $chapterBtn->isValid()) {
            $this->createAndAddChapter($newChapter, $module);
            $this->flushUpdatedModule($module);
        }

        if ($uploader->isSubmitted() && $uploader->isValid()) {
            $prevImage = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['type' => 'module', 'src' => $module->getImage()]);
            $updatedModule = $imageManager->changeModuleImage($uploader->getData()['upload'], $prevImage, $module, $user, $this->getParameter('uploads_directory'));
            $this->flushUpdatedModule($updatedModule);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedModule = $form->getData();
            $this->flushUpdatedModule($updatedModule);
            return $this->redirectToRoute('partner');
        }

        return $this->render('edit_module/index.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
            'addchapter' => $chapterBtn->createView(),
            'uploader' => $uploader->createView(),
        ]);
    }

    /**
     * @param Chapter $newChapter
     * @param LearningModule|null $module
     */
    public function createAndAddChapter(Chapter $newChapter, LearningModule $module): void
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        foreach ($languageAll as $language) {
            $emptyChapterTranslation = new ChapterTranslation($language, $newChapter);
            $newChapter->addTranslation($emptyChapterTranslation);
        }
        $chapterCount = count($module->getChapters());
        $newChapter->setChapterNumber(++$chapterCount);
        $newQuiz = new Quiz();
        $newChapter->setQuiz($newQuiz);
        $module->addChapter($newChapter);
    }

    /**
     * @param LearningModule $updatedModule
     */
    public function flushUpdatedModule(LearningModule $updatedModule): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($updatedModule);
        $entityManager->flush();
    }
}
