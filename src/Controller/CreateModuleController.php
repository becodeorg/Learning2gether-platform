<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\ImageManager;
use App\Domain\LearningModuleType;
use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\ChapterTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\Quiz;
use App\Entity\User;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateModuleController extends AbstractController
{
    /**
     * @Route("/partner/create/module", name="create_module")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        // initialize new module and one translation in english
        $module = new LearningModule();
        $english = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
        $newTrans = new LearningModuleTranslation($module, $english);
        $module->addTranslation($newTrans);

        // create the form
        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        // check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) {

            $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();

            $imageManager = new ImageManager();

            $module = $form->getData();
            $module = $this->makeModuleTranslations($module, $languageAll);

            $newImage = $imageManager->createImage($request->files->get('create_module')['image'], $user, $this->getParameter('uploads_directory'), 'module');
            $this->flushNewImage($newImage);
            $module->setImage($newImage->getSrc());

            // make new chapter and its translations
            $chapter = new Chapter($module);
            $chapter = $this->makeChapterTranslations($chapter, $languageAll);

            // make quiz and attach it to chapter
            $quiz = new Quiz();
            $chapter->setQuiz($quiz);

            // add chapter to module and flush
            $module->addChapter($chapter);
            $module = $this->flushNewModule($module);

            return $this->redirectToRoute('create_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
        }

        return $this->render('create_module/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param LearningModule $module
     * @param array $languageAll
     * @return LearningModule
     */
    private function makeModuleTranslations(LearningModule $module, array $languageAll): LearningModule
    {
        foreach ($languageAll as $language) {
            if ($language->getCode() !== 'en') {
                $translation = new LearningModuleTranslation($module, $language);
                $module->addTranslation($translation);
            }
        }
        return $module;
    }

    /**
     * @param Chapter $chapter
     * @param array $languageAll
     * @return Chapter
     */
    private function makeChapterTranslations(Chapter $chapter, array $languageAll): Chapter
    {
        foreach ($languageAll as $language) {
            $translation = new ChapterTranslation($language, $chapter);
            $chapter->addTranslation($translation);
        }
        return $chapter;
    }

    /**
     * @param LearningModule $module
     * @return LearningModule
     */
    private function flushNewModule(LearningModule $module): LearningModule
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($module);

        $cat = new Category();
        $cat->setLearningModule($module);
        $entityManager->persist($cat);

        $entityManager->flush();

        return $module;
    }

    public function flushNewImage(Image $image): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();
    }
}
