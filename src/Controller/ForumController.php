<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(Request $request)
    {
        $allCategories = !isset($_GET['mode']) ?
            $this->getDoctrine()->getRepository(Category::class)->findall()
            : $this->getDoctrine()->getRepository(Category::class)->findByType($_GET['mode']);

        return $this->render('forum/index.html.twig', [
            'allCategories' => $allCategories,
        ]);
    }
}
