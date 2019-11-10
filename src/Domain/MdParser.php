<?php
declare(strict_types=1);

namespace App\Domain;


class MdParser
{
    public function youtubeParser(string $html)
    {
        return preg_replace("/\s*[a-zA-Z\/:.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/*\-_?&;%=.]*)/i", '<iframe width="420" height="315" src="//www.youtube.com/embed/$1" allowfullscreen></iframe>', $html);
    }
}