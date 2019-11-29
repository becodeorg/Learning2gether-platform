<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardModuleController extends AbstractController
{
    /**
     * @Route("partner/dashboard/module/{module}", name="dashboard_module", requirements={"module"= "\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return Response
     */
    public function index(Request $request, LearningModule $module) : Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);
        $allLanguages = $this->getDoctrine()->getRepository(Language::class)->findAll();

        return $this->render('dashboard_module/index.html.twig', [
            'module' => $module,
            'language' => $language,
            'allLanguages' => $allLanguages,
        ]);
    }
}
