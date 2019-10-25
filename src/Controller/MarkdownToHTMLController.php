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
        $user_markdown = $form->getData();
        
        if($form->isSubmitted() && $form->isValid()) {
            $user_markdown;
            if(preg_grep("/(?:https?:\/\/)?www\.(?:youtube\.com|youtu\.be)\S+?v=\K\S+/i", $user_markdown)) {
                $search = "/(?:youtube\.com|youtu\.be)\/watch\?v=([a-zA-Z0-9]+)/i";
                $replace = '<center><iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></center>';

                $iPlayer = preg_replace($search, $replace, $user_markdown);

                echo $iPlayer['page_content'];
                // ^^returns content of this array
            }
        }

        
        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
            'form' => $form->createView(),
            'user_markdown' => $form->getData()['page_content'], 
        ]);
    }
}
