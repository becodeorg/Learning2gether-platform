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
            $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);
        } else {
            return $this->redirectToRoute('partner');
        }

        $languagesAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);
        foreach ($languagesAll as $language) {
            $translations = $this->getDoctrine()->getRepository(LearningModuleTranslation::class)->findBy([
                'language' => $language->getId(),
                'learningModule' => $moduleID]);
            foreach ($translations as $translation) {
                $module->addTranslation($translation);
            }
        }

        $form = $this->createForm(EditModuleType::class, $module);
        $form->handleRequest($request);

        return $this->render('edit_module/index.html.twig', [
            'controller_name' => 'EditModuleController',
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }
}
