<?php

declare(strict_types=1);

namespace App\Twig;

use Parsedown;
use ParsedownExtra;
use ParsedownExtraPlugin;
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

    public function searchForEmbed(string $text): string
    {
        $regex = '/.*!!\{(embed)\}\((.*)\)/';
        return preg_replace($regex, '<iframe src="https://www.youtube.com/embed/$2" allowfullscreen></iframe>', $text);
    }

    public function parsedown(string $text): string
    {
        /**
         * Improve the html generated from the markdown code.
         * doc: https://github.com/pixeline/parsedown-extra-plugin
         */
        $parsedown = new ParsedownExtraPlugin();
        $parsedown->setSafeMode(true);
        $parsedown->linkAttributes = function ($Text, $Attributes, &$Element, $Internal) {
            if (!$Internal) {
                return [
                    'rel' => 'nofollow',
                    'target' => '_blank'
                ];
            }
            return [];
        };
        return $parsedown->text($text);
    }
}
