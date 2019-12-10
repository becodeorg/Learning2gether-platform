<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIModulesController extends AbstractController
{
    /**
     * @Route("/api/module/{module}", name="api_module", requirements={"module"= "\d+"}, options={"expose"=true})
     * @param LearningModule $module
     * @param Request $request
     * @return JsonResponse
     */
    public function index(LearningModule $module, Request $request): JsonResponse
    {
        $lmr = $this->getDoctrine()->getRepository(LearningModule::class);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();
        $moduleArray = $lmr->getFullModuleAsArray($module)[0];
        $moduleArray['translations'] = $lmr->getModuleTranslationsAsArray($module)[0]['translations'];

        $fm = new FlaggingManager($languageCount);
        $flagData = $fm->checkModuleFull($moduleArray);
        $result = $fm->checkFlagData($flagData);

        return new JsonResponse($result);
    }
}
