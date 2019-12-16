<?php

namespace App\Controller;

use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PublishModuleController extends AbstractController
{
    /**
     * @Route("partner/publish/module/{module}", name="publish_module", requirements={"module"="\d+"} , options={"expose"=true})
     * @param LearningModule $module
     * @return RedirectResponse
     */
    public function index(LearningModule $module): RedirectResponse
    {
        $module->setIsPublished(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($module);
        $em->flush();
        return $this->redirectToRoute('dashboard');
    }
}
