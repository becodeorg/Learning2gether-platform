<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {
        //todo add const to format.
        //$category = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('1');
//        $category = $this->getDoctrine()->getRepository(Category::class)->find('1');
//        var_dump($category);
//       $categoryTranslations = $this->getDoctrine()->getRepository(CategoryTranslation::class)->findOneBy(['id' => 1]);
//       echo $categoryTranslations->getTitle();
//        $topic = $this->getDoctrine()->getRepository(Topic::class)->find('1')->getSubject();
//        var_dump($topic);
//        $date = $this->getDoctrine()->getRepository(Topic::class)->find('1')->getDate();
//        var_dump($date);
//        $post = $this->getDoctrine()->getRepository(Post::class)->find('1')->getSubject();
//        var_dump($post);

        $categoryTranslations = $this->getDoctrine()->getRepository(CategoryTranslation::class)->findAll();
        var_dump($categoryTranslations);

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
