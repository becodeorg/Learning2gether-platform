<?php
declare(strict_types=1);

namespace App\Twig;

use Parsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownParserExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('youtube', [$this, 'searchForEmbed']),
            new TwigFilter('parsedown', [$this, 'parsedown']),
        ];
    }

    public function searchForEmbed(string $text) : string
    {
        $regex = '/.*!!\{(embed)\}\((.*)\)/';
        return preg_replace($regex, '<iframe src="https://www.youtube.com/embed/$2" allowfullscreen></iframe>', $text);
    }

    public function parsedown(string $text) : string
    {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);

        return $parsedown->text($text);
    }
}