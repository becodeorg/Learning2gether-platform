<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterTranslation;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\Quiz;
use App\Form\CreateChapterType;
use App\Form\EditModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditModuleController extends AbstractController
{
    /**
     * @Route("/edit/module", name="edit_module")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // check the $_GET['module'], has to be set, and an integer, if not, redirects back to partner zone
        if (isset($_GET['module']) && ctype_digit((string)$_GET['module'])) {
            $moduleID = $_GET['module'];
        } else {
            return $this->redirectToRoute('partner');
        }

        $module = $this->getModuleAndTranslations($moduleID);
        $newChapter = new Chapter($module);

        $form = $this->createForm(EditModuleType::class, $module);
        $form->handleRequest($request);
        $chapterBtn = $this->createForm(CreateChapterType::class, $newChapter);
        $chapterBtn->handleRequest($request);

        if ($chapterBtn->isSubmitted() && $chapterBtn->isValid()){
            $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
            foreach ($languageAll as $language) {
                $emptyChapterTranslation = new ChapterTranslation($language, '', $newChapter);
                $newChapter->addTranslation($emptyChapterTranslation);
            }
            $chapterCount = count($module->getChapters());
            $newChapter->setChapterNumber(++$chapterCount);
            $newQuiz = new Quiz();
            $newChapter->setQuiz($newQuiz);
            $module->addChapter($newChapter);
            $this->flushUpdatedModule($module);
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
        ]);
    }

    /**
     * @param $moduleID
     * @return LearningModule|object|null
     */
    public function getModuleAndTranslations(int $moduleID) : LearningModule
    {
        // Preparing a module object for the form
        // Gets all languages from DB
        $languagesAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        // Gets the current module from DB
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);
        // foreach language in the DB, find the translation for the current module
        foreach ($languagesAll as $language) {
            $translations = $this->getDoctrine()->getRepository(LearningModuleTranslation::class)->findBy([
                'language' => $language->getId(),
                'learningModule' => $moduleID
            ]);
            // add all found translations to the module object
            foreach ($translations as $translation) {
                $module->addTranslation($translation);
            }
        }
        return $module;
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
