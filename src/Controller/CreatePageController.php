<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreatePageController extends AbstractController
{
    /**
     * @Route("partner/create/module/{module}/chapter/{chapter}/page", name="create_page")
     */
    public function index()
    {
        return $this->render('create_page/index.html.twig', [

        ]);
    }
}
