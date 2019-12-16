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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('dashboard');
        // should probably remove this middle page
    }
}
