<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Form\SearchbarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {
        $languageDummy = $this->getDoctrine()->getRepository(Language::class)->find('1');
        //hard coded out of scope of current ticket
        $allCategories = $this->getDoctrine()->getRepository(Category::class)->findall();

        $searchbar = $this->createForm(
            SearchbarType::class, [
            'search' => ''
        ], [
                'action' => $this->generateUrl('searchbar')
            ]
        )->createView();

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'CategoryController',
            'allCategories' => $allCategories,
            'language' => $languageDummy,
            'searchbar' => $searchbar,
        ]);
    }
}
