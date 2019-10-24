<?php

namespace App\Controller;

use App\Entity\LearningModule;
use App\Form\CreateModuleType;
use App\Form\MergeModuleType;
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
        $form = $this->createForm(MergeModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $module->setIsPublished(0);
//            $module->setBadge('no badge yet');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

        }

        return $this->render('partner/index.html.twig', [
            'controller_name' => 'PartnerController',
            'createModule' => $form->createView()
        ]);
    }
}
