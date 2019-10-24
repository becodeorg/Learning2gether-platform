<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MarkdownUserInputType;

class MarkdownToHTMLController extends AbstractController
{
    /**
     * @Route("/markdown", name="markdown_to_html")
     */
    public function index()
    {
        $form = $this->createForm(MarkdownUserInputType::class);

        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
            'form' => $form->createView(),
        ]);
    }
}
