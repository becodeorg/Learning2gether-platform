<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    /**
     * @Route("/partner", name="partner")
     * @param Request $request
     * @return Response
     */
    public function Index(Request $request): Response
    {
        $module = new LearningModule();
        $translationOne = new LearningModuleTranslation();
        $english = new Language();
        $english->setName('English');
        $translationOne->setLanguage($english);
        $module->getTranslations()->add($translationOne);

        $translationTwo = new LearningModuleTranslation();
        $spanish = new Language();
        $english->setName('Spanish');
        $translationTwo->setLanguage($spanish);
        $module->getTranslations()->add($translationTwo);

        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        return $this->render('partner/index.html.twig', [
            'controller_name' => 'PartnerController',
            'form' => $form->createView(),
        ]);
    }
}
