<?php

namespace App\Controller;

use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditModuleController extends AbstractController
{
    /**
     * @Route("/edit/module", name="edit_module")
     */
    public function index(): Response
    {
        // check the $_GET['module'], has to be set, and an integer, if not, redirects back to partner zone
        if (isset($_GET['module']) && ctype_digit((string)$_GET['module'])) {
            $moduleID = $_GET['module'];
            $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($moduleID);
        } else {
            return $this->redirectToRoute('partner');
        }

        return $this->render('edit_module/index.html.twig', [
            'controller_name' => 'EditModuleController',
            'module' => $module,
        ]);
    }
}
