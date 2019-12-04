<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Form\SearchbarType;
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
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);
        $allCategories = $this->getDoctrine()->getRepository(Category::class)->findall();

        $tmpCats = [$allCategories[0] ,$allCategories[0] ,$allCategories[0] ,$allCategories[0] ,$allCategories[0]];


        return $this->render('forum/index.html.twig', [
            'allCategories' => $tmpCats,
            'language' => $language,
        ]);
    }
}
