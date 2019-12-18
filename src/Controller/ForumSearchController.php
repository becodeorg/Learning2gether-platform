<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Question;
use App\Form\SearchbarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumSearchController extends AbstractController
{
    /**
     * @Route("/portal/forum/searchbar", name="searchbar")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SearchbarType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Post::class);
        $query = $repo->createQueryBuilder('p')
            ->where('p.subject LIKE :keyword')
            ->setParameter('keyword', '%' . $form->get('keywords')->getData() . '%')
            ->getQuery();
        $resultsFromPost = $query->getResult();

        $repo = $em->getRepository(Question::class);
        $query = $repo->createQueryBuilder('p')
            ->where('p.subject LIKE :keyword')
            ->setParameter('keyword', '%' . $form->get('keywords')->getData() . '%')
            ->getQuery();
        $resultsFromQuestion = $query->getResult();

        return $this->render('forum_search/index.html.twig', [
            'resultsFromPost' => $resultsFromPost,
            'resultsFromQuestion' => $resultsFromQuestion,

        ]);
    }

    public function getSearchbar(): FormView
    {
        $searchbar =  $this->createForm(
            SearchbarType::class, [
            'search' => ''
        ], [
                'action' => $this->generateUrl('searchbar')
            ]
        );
        return $searchbar->createView();
    }
}
