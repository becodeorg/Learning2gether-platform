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
            //get form data
            $user_markdown;
            //if there is a youtube link present in the $user_markdown input
            if(preg_grep("/(?:https?:\/\/)?www\.(?:youtube\.com|youtu\.be)\S+?v=\K\S+/i", $user_markdown)) {
                //if anyone needs I made an extra regex for checking that the string from beginning to end is a youtube link: (?=https:\/\/www\.(?:youtube\.com|youtu\.be)\/watch\?v=([a-zA-Z0-9\_]{11})&list=([a-zA-Z]{5})-([a-zA-Z]{9})&index=([0-9]+))(?<!([a-zA-Z]))
                $search = "/(?:youtube\.com|youtu\.be)\/watch\?v=([a-zA-Z0-9]+)/i";
                //declare what to replace the found link with
                $replace = '<center><iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></center>';
                //actually replace the found link with the iplayer
                $iPlayer = preg_replace($search, $replace, $user_markdown);
                //show it on screen
                echo $iPlayer['page_content'];
                // ^^returns content of this array
            }
        }

        
        return $this->render('markdown_to_html/index.html.twig', [
            'controller_name' => 'MarkdownToHTMLController',
            'form' => $form->createView(),
            //render user input on screen
            'user_markdown' => $form->getData()['page_content'], 
        ]);
    }
}
