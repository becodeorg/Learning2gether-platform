<?php

namespace App\Fixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $languages = [
            'en' => 'English',
            'es' => 'Español',
            'is' => 'Íslenska',
            'fr' => 'Français',
            'bg' => 'български'
        ];

        // create 20 products! Bam!
        foreach ($languages AS $languageCode => $languageName) {
            $language = new Language($languageName, $languageCode);
            $manager->persist($language);
        }

        $manager->flush();
    }
}