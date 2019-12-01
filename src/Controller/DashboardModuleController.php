<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
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
    public function index(Request $request, LearningModule $module): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();

        $moduleArray = $this->getDoctrine()->getRepository(LearningModule::class)->getModuleAsArray($module);
        $fm = new FlaggingManager();
        $flagData = $fm->checkModule($moduleArray[0], $languageCount);

        return $this->render('dashboard_module/index.html.twig', [
            'module' => $module,
            'moduleArray' => $moduleArray[0],
            'language' => $language,
            'flagData' => $flagData,
            'languagecount' => $languageCount,
        ]);
    }
}
