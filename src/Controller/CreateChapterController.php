<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreateChapterController extends AbstractController
{
    /**
     * @Route("/create/chapter", name="create_chapter")
     */
    public function index()
    {
        return $this->render('create_chapter/index.html.twig', [
            'controller_name' => 'CreateChapterController',
        ]);
    }
}
