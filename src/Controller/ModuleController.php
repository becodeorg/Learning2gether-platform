<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    /**
     * @Route("/module", name="app_module")
     */
    public function module(): Response
    {
        $module = $_GET['module'] ?? null;

        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'module' => $module, // module ID
        ]);
    }
}
