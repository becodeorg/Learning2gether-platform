<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\Language;
use Symfony\Component\HttpFoundation\Request;

trait LanguageTrait
{
    private function getLanguage(Request $request) : Language
    {
        return $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $request->getLocale()
        ]);
    }
}