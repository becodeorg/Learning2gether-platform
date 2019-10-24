<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarkdownToHTMLController extends AbstractController
{
    /**
     * @Route("/markdown", name="markdown_to_html")
     */
    public function index()
    {
        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
        ]);
    }
}
