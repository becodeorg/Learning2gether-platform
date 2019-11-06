<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditPageController extends AbstractController
{
    /**
     * @Route("/edit/page", name="edit_page")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('edit_page/index.html.twig', [
            'controller_name' => 'EditPageController',
        ]);
    }
}
