<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarkdownToHTMLController extends AbstractController
{
    /**
     * @Route("/markdown/to/h/t/m/l", name="markdown_to_h_t_m_l")
     */
    public function index()
    {
        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
        ]);
    }
}
