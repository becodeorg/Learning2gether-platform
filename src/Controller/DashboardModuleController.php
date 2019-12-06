<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Domain\LanguageTrait;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardModuleController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("partner/dashboard/module/{module}", name="dashboard_module", requirements={"module"= "\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return Response
     */
    public function index(Request $request, LearningModule $module): Response
    {
        $language = $this->getLanguage($request);
        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();
        $moduleArray = $this->getDoctrine()->getRepository(LearningModule::class)->getModuleAsArray($module);
        $chapterRepo = $this->getDoctrine()->getRepository(Chapter::class);

        $fm = new FlaggingManager($languageCount);

        return $this->render('dashboard_module/index.html.twig', [
            'fm' => $fm,
            'module' => $module,
            'moduleArray' => $moduleArray[0],
            'cr' => $chapterRepo,
            'language' => $language,
            'languagecount' => $languageCount,
        ]);
    }
}
