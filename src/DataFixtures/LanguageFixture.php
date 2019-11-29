<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixture extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $languages = [
            'en' => 'English',
            'es' => 'Español',
            'is' => 'Íslenska',
            'fr' => 'Français',
            'bg' => 'български'
        ];

        foreach ($languages AS $languageCode => $languageName) {
            $language = new Language($languageName, $languageCode);
            $manager->persist($language);
        }

        $manager->flush();
    }
}