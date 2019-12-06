<?php

namespace App\Controller;

use App\Domain\FlaggingManager;
use App\Domain\LanguageTrait;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Repository\LearningModuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("partner/dashboard", name="dashboard")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        // get the current language
        $language = $this->getLanguage($request);

        $languageCount = $this->getDoctrine()->getRepository(Language::class)->getLanguageCount();

        // fetch all LM objects from the DB
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();

        $lmRepo = $this->getDoctrine()->getRepository(LearningModule::class);
        $fm = new FlaggingManager($languageCount);

        return $this->render('dashboard/index.html.twig', [
            'allModules' => $allModules,
            'language' => $language,
            'lmRepo' => $lmRepo,
            'fm' => $fm,
            'languagecount' => $languageCount,
        ]);
    }
}
