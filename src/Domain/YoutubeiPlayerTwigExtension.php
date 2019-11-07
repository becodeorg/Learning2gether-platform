<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Form\MarkdownUserInputType;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('iPlayer', [$this, 'openYoutubeiPlayer']),
        ];
    }

    public function openYoutubeiPlayer($content)
    {
        $form = $this->createForm(MarkdownUserInputType::class);
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
    }
}