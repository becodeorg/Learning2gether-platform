<?php


namespace App\Domain;


class FlaggingManager
{
    private const MIN_NEEDED_TRANSLATIONS = 2;

    public function checkModuleTranslations(array $moduleData, int $languageCount): array
    {
        $flagData = [];

        $flagData['moduleNeededTranslations'] = [];
        $flagData['moduleStatus'] = false;
        foreach ($moduleData['translations'] as $moduleTranslation) {
            if ($moduleTranslation['title'] === '' || $moduleTranslation['description'] === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation['language']['name'];
            }
        }
        if ((count($flagData['moduleNeededTranslations']) - $languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['moduleStatus'] = true;
        }

        return $flagData;
    }

    public function checkChapter(array $chapterData, int $languageCount): array
    {
        $flagData = [];

        foreach ($chapterData as $chapter) {
            $flagData = $this->checkChapterTranslations($languageCount, $flagData, $chapter);
//            $flagData = $this->checkChapterPages($languageCount, $chapter, $flagData);

//            foreach ($chapter['quiz']['quizQuestions'] as $question) {
//                $flagData = $this->checkQuizTranslations($languageCount, $flagData, $question);
//                $flagData = $this->checkAnswers($languageCount, $question, $flagData);
//            }
        }

        return $flagData;
    }

    /**
     * @param int $languageCount
     * @param array $flagData
     * @param $chapter
     * @return array
     */
    public function checkChapterTranslations(int $languageCount, array $flagData, $chapter): array
    {
        if (!isset($flagData['chapters'][$chapter['chapterNumber']])) {
            $flagData['chapters'][$chapter['chapterNumber']] = [];
            $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'] = [];
            $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = false;
        }

        foreach ($chapter['translations'] as $chapterTranslation) {
            if ($chapterTranslation['title'] === '' || $chapterTranslation['description'] = '') {
                $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'][] = $chapterTranslation['language']['name'];
            }
        }
        if ((count($flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations']) - $languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = true;
        }
        return $flagData;
    }

    public function checkQuiz(array $quizData, int $languageCount): array
    {
        $flagData = [];

        foreach ($quizData['quizQuestions'] as $question) {
            $flagData = $this->checkQuizTranslations($languageCount, $flagData, $question);
            $flagData = $this->checkAnswers($languageCount, $question, $flagData);
        }
        return $flagData;

    }

    /**
     * @param int $languageCount
     * @param array $flagData
     * @param $question
     * @return array
     */
    public function checkQuizTranslations(int $languageCount, array $flagData, array $question): array
    {
        $flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'] = [];
        $flagData['quiz']['questions'][$question['questionNumber']]['questionStatus'] = false;
        foreach ($question['translations'] as $questionTranslation) {
            if ($questionTranslation['title'] === '') {
                $flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'][] = $questionTranslation['language']['name'];
            }
        }
        if ((count($flagData['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations']) - $languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['quiz']['questions'][$question['questionNumber']]['questionStatus'] = true;
        }
        return $flagData;
    }

    /**
     * @param int $languageCount
     * @param $question
     * @param array $flagData
     * @return array
     */
    public function checkAnswers(int $languageCount, $question, array $flagData): array
    {
        foreach ($question['answers'] as $answer) {
            $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'] = [];
            $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = false;
            foreach ($answer['translations'] as $answerTranslation) {
                if ($answerTranslation['title'] === '') {
                    $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'][] = $answerTranslation['language']['name'];
                }
            }
            if ((count($flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations']) - $languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
                $flagData['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = true;
            }
        }
        return $flagData;
    }

    /**
     * @param int $languageCount
     * @param array $pageArray
     * @return array
     */
    public function checkPage(array $pageArray, int $languageCount): array
    {
        $flagData = [];

        $flagData['pageNeededTranslations'] = [];
        $flagData['pageStatus'] = false;
        foreach ($pageArray['translations'] as $pageTranslation) {
            if ($pageTranslation['title'] === '' || $pageTranslation['content'] === '') {
                $flagData['pageNeededTranslations'][] = $pageTranslation['language']['name'];
            }
        }
        if ((count($flagData['pageNeededTranslations']) - $languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['pageStatus'] = true;
        }

        return $flagData;
    }

}