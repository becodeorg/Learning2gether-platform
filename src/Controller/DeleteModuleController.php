<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteModuleController extends AbstractController
{
    /**
     * @Route("/partner/delete/module/{module}", name="delete_module", requirements={"module"="\d+"})
     * @param Request $request
     * @param LearningModule $module
     * @return RedirectResponse
     */
    public function index(Request $request, LearningModule $module): RedirectResponse
    {
        $cat = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['learning_module' => $module]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cat);
        $em->remove($module);
        $em->flush();
        return $this->redirectToRoute('dashboard');
    }
}
