<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateModuleController extends AbstractController
{
    /**
     * @Route("/create/module", name="create_module")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // make a new module for the form, empty values for now
        $module = new LearningModule('', '', '');
        $translationArray = $this->makeTranslations($module);

        // create the form
        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        // check if the form is submitted/posted
        if ($form->isSubmitted() && $form->isValid()) {
            $newTranslations = $_POST['create_module']['translations'];
            if ($this->isOneTranslationFilledIn($newTranslations)) {
                $this->makePostedTranslations($newTranslations, $translationArray, $module);
                $this->flushNewModule($module);
                $this->addFlash('success','');
                $this->redirectToRoute('edit_module', ['module' => $module->getId()]);
            } else {
                echo 'Please fill in at least one language!';
            }
        }

        // after creation, go to module edit page?

        return $this->render('create_module/index.html.twig', [
            'controller_name' => 'CreateModuleController',
            'form' => $form->createView(),
        ]);
    }

    public function isOneTranslationFilledIn(array $translations): bool
    {
        //  function to check if at least one of the translations is filled in (both fields)
        foreach ($translations as $translation) {
            if (!empty($translation['title']) && !empty($translation['description'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param LearningModule $module
     * @return array
     */
    public function makeTranslations(LearningModule $module): array
    {
        // collects all different languages from the DB, and creates a translation object for each language
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        $translationArray = [];
        foreach ($languageAll as $language) {
            $languageDoctrine = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $language->getCode()]);
            $translation = new LearningModuleTranslation($module, $languageDoctrine);
            $translationArray[] = $translation;
            $module->addTranslation($translation); // adds them to render the form the form
        }
        return $translationArray;
    }

    /**
     * @param $newTranslations
     * @param array $translationArray
     * @param LearningModule $module
     */
    public function makePostedTranslations($newTranslations, array $translationArray, LearningModule $module): void
    {
        // take the posted titles and descriptions, set their values, and add them to the module
        foreach ($newTranslations as $key => $translation) {
            $translationArray[$key]->setTitle($translation['title']);
            $translationArray[$key]->setDescription($translation['description']);
        }
        // add all translations to the new module
        foreach ($translationArray as $translation) {
            $module->addTranslation($translation);
        }
    }

    /**
     * @param LearningModule $module
     */
    public function flushNewModule(LearningModule $module): void
    {
        // flush the new module to the DB (the translations are set to cascade(src/Entity/LearningModule.php:44))
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($module);
        $entityManager->flush();
    }
}
