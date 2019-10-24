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
        //todo add category_id to topics.
       $categoryNow = $this->getDoctrine()->getRepository(CategoryTranslation::class)->find('2')->getTitle();
      // $topicNow = $this->getDoctrine()->getRepository(Topic::class)->find('1');
       //var_dump($topicNow);
       $topicNow = 'happy';
        $post = $this->getDoctrine()->getRepository(Post::class)->find('1');
        $postNow = $post->getSubject();
        $postNowDate = $post->getDate()->format('Y-m-d H:i:s');
        $post = $this->getDoctrine()->getRepository(Post::class)->find('2');
        $postNow1 = $post->getSubject();
       $postNowDate1 = $post->getDate()->format('Y-m-d H:i:s');
       return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
           'category_now' => $categoryNow,
          'topic_now' => $topicNow,
          'post_now' => $postNow,
           'post_now_date' => $postNowDate,
           'post_now1' => $postNow1,
           'post_now_date1' => $postNowDate1,


        ]);
    }
}
