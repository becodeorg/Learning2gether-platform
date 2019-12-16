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
        $moduleArray = $lmr->getFullModuleAsArray($module);

        if (empty($moduleArray[0])) {
            return new JsonResponse(['This module is still missing children, check to see if all chapters have at least one page, check every question in the quiz has at least one answer']);
        }

        $moduleArray[0]['translations'] = $lmr->getModuleTranslationsAsArray($module)[0]['translations'];

        $fm = new FlaggingManager($languageCount);
        $flagData = $fm->checkModuleFull($moduleArray[0]);

        $result = $fm->checkFlagData($flagData);
        return new JsonResponse($result);
    }
}
