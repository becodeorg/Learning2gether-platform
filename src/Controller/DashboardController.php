<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Repository\LearningModuleRepository;
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

        // get the current language
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);

        // fetch all LM objects from the DB
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'allModules' => $allModules,
            'language' => $language,
        ]);
    }
}
