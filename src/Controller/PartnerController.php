<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    /**
     * @Route("/partner", name="partner")
     * @return Response
     */
    public function index(): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->find(1);
        $modules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();
        return $this->render('partner/index.html.twig', [
            'controller_name' => 'PartnerController',
            'language' => $language,
            'modules' => $modules,
        ]);
    }
}
