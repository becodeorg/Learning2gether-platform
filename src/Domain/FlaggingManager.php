<?php


namespace App\Domain;


class FlaggingManager
{
    public function flagCheck(array $moduleData, int $languageCount) : array
    {
        //function to check the module in order to show it requires more translations before publishing
        $flagData = [];

        $flagData['moduleStatus'] = false;
        foreach ($moduleData['translations'] as $moduleTranslation) {
            if ($moduleTranslation['title'] === '' || $moduleTranslation['description'] === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation['language']['name'];
            }
        }
        if ((count($flagData['moduleNeededTranslations']) - $languageCount) <= -2 ){
            $flagData['moduleStatus'] = true;
        }

        return $flagData;
    }
}