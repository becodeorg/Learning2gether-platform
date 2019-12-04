<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizAnswerTranslationRepository")
 */
class QuizAnswerTranslation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Language")
     * @ORM\JoinColumn(nullable=false)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuizAnswer", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizAnswer;

    public function __construct(QuizAnswer $quizAnswer, Language $language, string $title = '')
    {
        $this->language = $language;
        $this->title = $title;
        $this->quizAnswer = $quizAnswer;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getQuizAnswer(): QuizAnswer
    {
        return $this->quizAnswer;
    }

    public function setQuizAnswer(QuizAnswer $quizAnswer): self
    {
        $this->quizAnswer = $quizAnswer;

        return $this;
    }
}
