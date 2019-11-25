<?php


namespace App\Domain;


class MdParser
{
    public function searchForEmbed(string $text)
    {
        $regex = '/.*!!\{(embed)\}\((.*)\)/';
        return preg_replace($regex, '<iframe width="560" height="315" src="https://www.youtube.com/embed/$2" allowfullscreen></iframe>', $text);
    }
}