<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
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

        $form = $this->createForm(EditModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedModule = $form->getData();
            $this->flushUpdatedModule($updatedModule);
            return $this->redirectToRoute('partner');
        }

        return $this->render('edit_module/index.html.twig', [
            'controller_name' => 'EditModuleController',
            'module' => $module,
            'form' => $form->createView(),
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
