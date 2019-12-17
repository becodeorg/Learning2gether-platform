<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\EditModuleTranslationsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditModuleTranslationController extends AbstractController
{
    /**
     * @Route("partner/edit/translation/module/{module}", name="edit_module_translation", requirements={"module"="\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return RedirectResponse|Response
     */
    public function index(Request $request, LearningModule $module) : Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $_GET['lang']
        ]);

        if ($language === null){
            return $this->redirectToRoute('partner');
        }

        $moduleTL = $this->getDoctrine()->getRepository(LearningModuleTranslation::class)->findOneBy(['language' => $language, 'learningModule' => $module]);

        $form = $this->createForm(EditModuleTranslationsType::class, $moduleTL);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $moduleTL = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($moduleTL);
            $em->flush();
            $this->addFlash('success', 'Changes saved successfully!');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('edit_module_translation/index.html.twig', [
            'form' => $form->createView(),
            'module' => $module,
        ]);
    }
}
