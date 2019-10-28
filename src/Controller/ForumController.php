<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {

        $categoryID = $this->getDoctrine()->getRepository(Category::class)->find('1')->getId();
        $category = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('1')->getTitle();
        $topic = $this->getDoctrine()->getRepository(Topic::class)->find('1')->getSubject();
        $topicDate = $this->getDoctrine()->getRepository(Topic::class)->find('1')->getDate()->format('Y-m-d H:i:s');;
        $post = $this->getDoctrine()->getRepository(Post::class)->find('1')->getSubject();
        $postDate = $this->getDoctrine()->getRepository(Post::class)->find('1')->getDate()->format('Y-m-d H:i:s');;



        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'categoryID' => $categoryID,
            'category' => $category,
            'topic' => $topic,
            'topic_date' => $topicDate,
            'post' => $post,
            'post_date' => $postDate
        ]);
    }
}
