<?php
declare(strict_types=1);

namespace App\Domain;


use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use Doctrine\ORM\EntityManager;

class QuizManager
{
    public const FAIL = 'FAIL';
    public const FINISHED_CHAPTER = 'FINISHED_CHAPTER';
    public const FINISHED_MODULE = 'FINISHED_MODULE';

    private $quiz;
    private $questions;
    private $status = null;

    public function __construct(Quiz $quiz, array $questions)
    {
        $this->quiz = $quiz;
        $this->questions = $questions;
    }

    public function getPercentageResult() : int
    {
        $correctAnswers = 0;
        foreach ($this->quiz->getQuizQuestions() AS $question) {
            if(!isset($this->questions[$question->getId()])) {
                continue;
            }

            $answer = $question->getAnswerById(
                (int)$this->questions[$question->getId()]
            );

            $correctAnswers += $answer === null ? 0 : $answer->IsCorrect();
        }

        return (int)round($correctAnswers / count($this->quiz->getQuizQuestions()) * 100);
    }

    public function getStatus() : string
    {
        if($this->status === null) {
            $this->status = self::FAIL;
            if($this->getPercentageResult() >= Quiz::MINIMUM_PERCENTAGE) {
                $this->status = self::FINISHED_CHAPTER;
            }

            $chapterManager = new ChapterManager($this->quiz->getChapter());
            if($this->status === self::FINISHED_CHAPTER && $chapterManager->isLast()) {
                $this->status = self::FINISHED_MODULE;
            }
        }

        return $this->status;
    }
}