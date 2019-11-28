<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("partner/dashboard", name="dashboard")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();
        $allLanguages = $this->getDoctrine()->getRepository(Language::class)->findAll();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'allModules' => $allModules,
            'allLanguages' => $allLanguages,
            'language' => $language,
        ]);
    }
}
