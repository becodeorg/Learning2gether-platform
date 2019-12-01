<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
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
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();

        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();
        $array = [];
        foreach ($allModules as $module) {
            $array[] = $this->getDoctrine()->getRepository(LearningModule::class)->getModuleAsArray($module);
        }
        $fm = new FlaggingManager();
        $flagArray = [];
        foreach ($array as $moduleArray) {
            if (!empty($moduleArray)){ // see LM repository for fix-me
                $flagArray[] = $fm->checkModule($moduleArray[0], $languageCount);
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'fm' => $fm,
            'array' => $array,
            'flagArray' => $flagArray,
            'allModules' => $allModules,
            'languageCount' => $languageCount,
            'language' => $language,
        ]);
    }
}
