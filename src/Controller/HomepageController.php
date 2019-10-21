<?php

namespace App\Controller;

//use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        //$user = new User();

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'name' => 'Tim',
        ]);
    }
}
