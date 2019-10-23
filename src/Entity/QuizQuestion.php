<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizQuestionRepository")
 */
class QuizQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $questionNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizQuestionTranslation", mappedBy="quizQuestion", orphanRemoval=true)
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="quizQuestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizAnswer", mappedBy="quizQuestion", orphanRemoval=true)
     */
    private $answers;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionNumber(): ?int
    {
        return $this->questionNumber;
    }

    public function setQuestionNumber(int $questionNumber): self
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    /**
     * @return Collection|QuizQuestionTranslation[]
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(QuizQuestionTranslation $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setQuizQuestion($this);
        }

        return $this;
    }

    public function removeTranslation(QuizQuestionTranslation $translation): self
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
            // set the owning side to null (unless already changed)
            if ($translation->getQuizQuestion() === $this) {
                $translation->setQuizQuestion(null);
            }
        }

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection|QuizAnswer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(QuizAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuizQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(QuizAnswer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuizQuestion() === $this) {
                $answer->setQuizQuestion(null);
            }
        }

        return $this;
    }
}
