<?php


namespace App\Domain;


class FlaggingManager
{
    public function checkModuleSolo(array $moduleData, int $languageCount) : array
    {
        $flagData = [];

        $flagData['moduleNeededTranslations'] = [];
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

    public function checkModule(array $moduleData, int $languageCount) : array
    {
        //function to check the module in order to show it requires more translations before publishing
        $flagData = [];

        $flagData['moduleNeededTranslations'] = [];
        $flagData['moduleStatus'] = false;
        foreach ($moduleData['translations'] as $moduleTranslation) {
            if ($moduleTranslation['title'] === '' || $moduleTranslation['description'] === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation['language']['name'];
            }
        }
        if ((count($flagData['moduleNeededTranslations']) - $languageCount) <= -2 ){
            $flagData['moduleStatus'] = true;
        }

        foreach ($moduleData['chapters'] as $chapter) {
            $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'] = [];
            $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = false;
            foreach ($chapter['translations'] as $chapterTranslation) {
                if ($chapterTranslation['title'] === '' || $chapterTranslation['description'] = ''){
                    $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'][] = $chapterTranslation['language']['name'];
                }
            }
            if ((count($flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations']) - $languageCount) <= -2 ){
                $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = true;
            }

            foreach ($chapter['pages'] as $page) {
                $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations'] = [];
                $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageStatus'] = false;
                foreach ($page['translations'] as $pageTranslation) {
                    if ($pageTranslation['title'] === '' || $pageTranslation['content'] === ''){
                        $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations'][] = $pageTranslation['language']['name'];
                    }
                }
                if ((count($flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations']) - $languageCount) <= -2 ){
                    $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageStatus'] = true;
                }
            }

            foreach ($chapter['quiz']['quizQuestions'] as $question) {
                $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'] = [];
                $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionStatus'] = false;
                foreach ($question['translations'] as $questionTranslation) {
                    if ($questionTranslation['title'] === ''){
                        $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'][] = $questionTranslation['language']['name'];
                    }
                }
                if ((count($flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations']) - $languageCount) <= -2 ){
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionStatus'] = true;
                }

                foreach ($question['answers'] as $answer) {
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'] = [];
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = false;
                    foreach ($answer['translations'] as $answerTranslation) {
                        if ($answerTranslation['title'] === ''){
                            $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'][] = $answerTranslation['language']['name'];
                        }
                    }
                    if ((count($flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations']) - $languageCount) <= -2 ){
                        $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = true;
                    }
                }
            }
        }

        return $flagData;
    }

    public function checkChapter(array $chapterData, int $languageCount): array
    {
        $flagData = [];

        foreach ($chapterData as $chapter) {
            $flagData['chapterNeededTranslations'] = [];
            $flagData['chapterStatus'] = false;
            foreach ($chapter['translations'] as $chapterTranslation) {
                if ($chapterTranslation['title'] === '' || $chapterTranslation['description'] = ''){
                    $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'][] = $chapterTranslation['language']['name'];
                }
            }
            if ((count($flagData['chapterNeededTranslations']) - $languageCount) <= -2 ){
                $flagData['chapterStatus'] = true;
            }

            foreach ($chapter['pages'] as $page) {
                $flagData['pages'][$page['pageNumber']]['pageNeededTranslations'] = [];
                $flagData['pages'][$page['pageNumber']]['pageStatus'] = false;
                foreach ($page['translations'] as $pageTranslation) {
                    if ($pageTranslation['title'] === '' || $pageTranslation['content'] === ''){
                        $flagData['pages'][$page['pageNumber']]['pageNeededTranslations'][] = $pageTranslation['language']['name'];
                    }
                }
                if ((count($flagData['pages'][$page['pageNumber']]['pageNeededTranslations']) - $languageCount) <= -2 ){
                    $flagData['pages'][$page['pageNumber']]['pageStatus'] = true;
                }
            }

            foreach ($chapter['quiz']['quizQuestions'] as $question) {
                $flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'] = [];
                $flagData['quiz']['questions'][$question['questionNumber']]['questionStatus'] = false;
                foreach ($question['translations'] as $questionTranslation) {
                    if ($questionTranslation['title'] === ''){
                        $flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'][] = $questionTranslation['language']['name'];
                    }
                }
                if ((count($flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations']) - $languageCount) <= -2 ){
                    $flagData['quiz']['questions'][$question['questionNumber']]['questionStatus'] = true;
                }

                foreach ($question['answers'] as $answer) {
                    $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'] = [];
                    $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = false;
                    foreach ($answer['translations'] as $answerTranslation) {
                        if ($answerTranslation['title'] === ''){
                            $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'][] = $answerTranslation['language']['name'];
                        }
                    }
                    if ((count($flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations']) - $languageCount) <= -2 ){
                        $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = true;
                    }
                }
            }
        }

        return $flagData;

    }
}