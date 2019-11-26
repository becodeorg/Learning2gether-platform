<?php

namespace App\Controller;

use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("partner/dashboard", name="dashboard")
     */
    public function index()
    {
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'allModules' => $allModules,
        ]);
    }
}
