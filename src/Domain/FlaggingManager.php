<?php


namespace App\Domain;


class FlaggingManager
{
    private const MIN_NEEDED_TRANSLATIONS = 2;

    /**
     * @var int
     */
    private $languageCount;

    /**
     * FlaggingManager constructor.
     * @param int $languageCount
     */
    public function __construct(int $languageCount)
    {
        $this->languageCount = $languageCount;
    }

    public function checkModuleFull(array $moduleData): array
    {
        $flagData = [];

        $flagData['moduleNeededTranslations'] = [];
        $flagData['moduleStatus'] = false;
        foreach ($moduleData['translations'] as $moduleTranslation) {
            if ($moduleTranslation['title'] === '' || $moduleTranslation['description'] === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation['language']['name'];
            }
        }
        if (!in_array('English', $flagData['moduleNeededTranslations'], true) && (count($flagData['moduleNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['moduleStatus'] = true;
        }

        $flagData['chapters'] = [];
        foreach ($moduleData['chapters'] as $chapter) {

            $flagData['chapters'][$chapter['chapterNumber']] = [];
            $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'] = [];
            $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = false;

            foreach ($chapter['translations'] as $chapterTranslation) {
                if ($chapterTranslation['title'] === '' || $chapterTranslation['description'] = '') {
                    $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'][] = $chapterTranslation['language']['name'];
                }
            }
            if (!in_array('English', $flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations'], true) && (count($flagData['chapters'][$chapter['chapterNumber']]['chapterNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
                $flagData['chapters'][$chapter['chapterNumber']]['chapterStatus'] = true;
            }

            foreach ($chapter['pages'] as $page) {
                $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']] = [];
                $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations'] = [];
                $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageStatus'] = false;
                foreach ($page['translations'] as $pageTranslation) {
                    if ($pageTranslation['title'] === '' || $pageTranslation['content'] === '') {
                        $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations'][] = $pageTranslation['language']['name'];
                    }
                }
                if (!in_array('English', $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations'], true) && (count($flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
                    $flagData['chapters'][$chapter['chapterNumber']]['pages'][$page['pageNumber']]['pageStatus'] = true;
                }
            }

            foreach ($chapter['quiz']['quizQuestions'] as $question) {
                $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'] = [];
                $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionStatus'] = false;
                foreach ($question['translations'] as $questionTranslation) {
                    if ($questionTranslation['title'] === '') {
                        $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'][] = $questionTranslation['language']['name'];
                    }
                }
                if (!in_array('English', $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations'], true) && (count($flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['questionStatus'] = true;
                }

                foreach ($question['answers'] as $answer) {
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'] = [];
                    $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = false;
                    foreach ($answer['translations'] as $answerTranslation) {
                        if ($answerTranslation['title'] === '') {
                            $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'][] = $answerTranslation['language']['name'];
                        }
                    }
                    if (!in_array('English', $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations'], true) && (count($flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
                        $flagData['chapters'][$chapter['chapterNumber']]['quiz']['questions'][$question['questionNumber']]['answers'][$answer['id']]['answerStatus'] = true;
                    }
                }
            }
        }

        return $flagData;
    }

    public function checkModuleTranslationsSolo(array $moduleData): array
    {
        $flagData = [];

        $flagData['moduleNeededTranslations'] = [];
        $flagData['moduleStatus'] = false;
        foreach ($moduleData['translations'] as $moduleTranslation) {
            if ($moduleTranslation['title'] === '' || $moduleTranslation['description'] === '') {
                $flagData['moduleNeededTranslations'][] = $moduleTranslation['language']['name'];
            }
        }
        if ((count($flagData['moduleNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['moduleStatus'] = true;
        }

        return $flagData;
    }

    public function checkChapterTranslationsSolo(array $chapterData): array
    {
        $flagData = [];
        $flagData['chapterNeededTranslations'] = [];
        $flagData['chapterStatus'] = false;

        foreach ($chapterData[0]['translations'] as $chapterTranslation) {
            if ($chapterTranslation['title'] === '' || $chapterTranslation['description'] = '') {
                $flagData['chapterNeededTranslations'][] = $chapterTranslation['language']['name'];
            }
        }
        if ((count($flagData['chapterNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['chapterStatus'] = true;
        }
        return $flagData;
    }

    public function checkQuizTranslationSolo(array $quizData, int $languageCount): array
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
     * @param array $pageArray
     * @return array
     */
    public function checkPageTranslationSolo(array $pageArray): array
    {
        $flagData = [];

        $flagData['pageNeededTranslations'] = [];
        $flagData['pageStatus'] = false;
        foreach ($pageArray['translations'] as $pageTranslation) {
            if ($pageTranslation['title'] === '' || $pageTranslation['content'] === '') {
                $flagData['pageNeededTranslations'][] = $pageTranslation['language']['name'];
            }
        }
        if ((count($flagData['pageNeededTranslations']) - $this->languageCount) <= -self::MIN_NEEDED_TRANSLATIONS) {
            $flagData['pageStatus'] = true;
        }

        return $flagData;
    }

    public function checkFlagData(array $flagData): array
    {
        $results = [];

        if (!$flagData['moduleStatus']) {
            $results[] = 'The Module needs more translations';
        }

        foreach ($flagData['chapters'] as $chapterNumber => $chapter) {
            if (!$chapter['chapterStatus']) {
                $results[] = 'Chapter ' . $chapterNumber . ' needs more translations';
            }

            foreach ($chapter['pages'] as $pageNumber => $page) {
                if (!$page['pageStatus']) {
                    $results[] = 'Chapter ' . $chapterNumber . ', Page ' . $pageNumber . ' needs more translations';
                }
            }
            foreach ($chapter['quiz']['questions'] as $questionNumber => $quizQuestion) {
                if (!$quizQuestion['questionStatus']) {
                    $results[] = 'Chapter ' . $chapterNumber . ', QuizQuestion ' . $questionNumber . ' needs more translations';
                }

                foreach ($quizQuestion['answers'] as $answer) {
                    if (!$answer['answerStatus']){
                        $results[] = 'Chapter ' . $chapterNumber . ', QuizQuestion ' . $questionNumber . ', an answer needs more translations';
                    }
                }
            }
        }

        return $results;

    }

}