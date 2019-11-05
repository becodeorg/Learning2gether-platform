<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    /**
     * @Route("/module", name="module")
     */
    public function module(): Response
    {
        // check the $_GET['module'], has to be set, and an integer, if not, redirects back to portal
        if (isset($_GET['module']) && ctype_digit((string)$_GET['module'])) {
            $moduleID = $_GET['module'];
        } else {
            return $this->redirectToRoute('partner');
        }

        $language = $this->getDoctrine()->getRepository(Language::class)->find(1);
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->findOneBy(['id' => $moduleID]);
        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'language' => $language,
            'module' => $module,
        ]);
    }
}