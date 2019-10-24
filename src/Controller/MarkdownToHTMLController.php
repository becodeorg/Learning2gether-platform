<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MarkdownUserInputType;
use Symfony\Component\HttpFoundation\Request;

class MarkdownToHTMLController extends AbstractController
{
    /**
     * @Route("/markdown", name="markdown_to_html")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(MarkdownUserInputType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user_markdown = $form->getData();
        }
        
        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
            'form' => $form->createView(),
            'user_markdown' => $form->getData()['page_content'], 
        ]);
    }
}
