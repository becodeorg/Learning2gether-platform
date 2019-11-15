<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\Quiz;
use App\Form\CreateChapterType;
use App\Form\EditModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        $module = $this->getModuleAndTranslations($module);
        $newChapter = new Chapter($module);
        $user = $this->getUser();

        $form = $this->createForm(EditModuleType::class, $module);
        $form->handleRequest($request);
        $chapterBtn = $this->createForm(CreateChapterType::class, $newChapter);
        $chapterBtn->handleRequest($request);

        $uploader = $this->createFormBuilder()
            ->add('upload', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $uploader->handleRequest($request);

        if ($chapterBtn->isSubmitted() && $chapterBtn->isValid()) {
            $this->createAndAddChapter($newChapter, $module);
            $this->flushUpdatedModule($module);
        }

        if ($uploader->isSubmitted() && $uploader->isValid()){
            $fileToDelete = $module->getImage();
            $uploadedImage = $uploader->getData()['upload'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid('', true)) . '.' . $uploadedImage->guessExtension();
            $prevImage = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['type' => 'module', 'src' => $fileToDelete]);
            $prevImage->setSrc($filename);
            $prevImage->setName($uploadedImage->getClientOriginalName());
            $user->addImage($prevImage);
            $module->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $uploadedImage->move(
                $uploads_directory,
                $filename
            );

            unlink($uploads_directory. '/' .$fileToDelete);

        }

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedModule = $form->getData();
            $this->flushUpdatedModule($updatedModule);
            return $this->redirectToRoute('partner');
        }

        return $this->render('edit_module/index.html.twig', [
            'controller_name' => 'EditModuleController',
            'module' => $module,
            'form' => $form->createView(),
            'addchapter' => $chapterBtn->createView(),
            'uploader' => $uploader->createView(),
        ]);
    }

    /**
     * @param LearningModule $module
     * @return LearningModule
     */
    public function getModuleAndTranslations(LearningModule $module): LearningModule
    {
        // Preparing a module object for the form
        // Gets all languages from DB
        $languagesAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        // Gets the current module from DB
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($module);
        // foreach language in the DB, find the translation for the current module
        foreach ($languagesAll as $language) {
            $translations = $this->getDoctrine()->getRepository(LearningModuleTranslation::class)->findBy([
                'language' => $language->getId(),
                'learningModule' => $module->getId()
            ]);
            // add all found translations to the module object
            foreach ($translations as $translation) {
                $module->addTranslation($translation);
            }
        }
        return $module;
    }

    /**
     * @param Chapter $newChapter
     * @param LearningModule|null $module
     */
    public function createAndAddChapter(Chapter $newChapter, ?LearningModule $module): void
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
        // Flush the updated module + translations to the DB
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($updatedModule);
        $entityManager->flush();
    }
}
