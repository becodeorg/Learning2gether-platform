<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PortalController extends AbstractController
{
    /**
     * @Route("/portal", name="portal")
     */
    public function index()
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->find(1);
        $modules = $this->getDoctrine()->getRepository(LearningModule::class)->findBy(['isPublished' => true]);
        return $this->render('portal/index.html.twig', [
            'controller_name' => 'PortalController',
            'language' => $language,
            'modules' => $modules,
        ]);
    }
}
