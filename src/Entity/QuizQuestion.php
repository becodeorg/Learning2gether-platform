<?php

namespace App\Entity;

use App\Domain\Breadcrumb;
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
     * @ORM\OneToMany(targetEntity="App\Entity\QuizQuestionTranslation", mappedBy="quizQuestion", orphanRemoval=true, cascade={"persist"})
     */
    private $translations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="quizQuestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizAnswer", mappedBy="quizQuestion", orphanRemoval=true, cascade={"persist"})
     */
    private $answers;

    public function __construct(int $questionNumber, Quiz $quiz)
    {
        $this->translations = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->questionNumber = $questionNumber;
        $this->quiz = $quiz;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionNumber(): int
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

    public function getQuiz(): Quiz
    {
        return $this->quiz;
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
        }

        return $this;
    }

    public function removeAnswer(QuizAnswer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
        }

        return $this;
    }

    public function getTitle(Language $language): ?string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getCode() === $language->getCode()) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
        return 'Not defined';
    }

    public function getAnswerById(int $id) : ?QuizAnswer
    {
        foreach($this->getAnswers() AS $answer) {
            if($answer->getId() === $id) {
                return $answer;
            }
        }
        return null;
    }

    public function getTrTitle(string $langCode): ?string
    {
        foreach ($this->getTranslations() AS $translation) {
            if ($translation->getLanguage()->getCode() === $langCode) {
                return $translation->getTitle();//change this line if needed when copied
            }
        }
        return 'Not defined';
    }

    public function getDashboardBreadcrumbs(Language $language) : array
    {
        $breadcrumbs = $this->getQuiz()->getDashboardBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb('Question', 'dashboard_question', ['chapter' => $this->getQuiz()->getChapter()->getId(), 'question' => $this->getId()]);
        return $breadcrumbs;
    }

    public function getEditBreadcrumbs(Language $language) : array
    {
        $breadcrumbs = $this->getQuiz()->getEditBreadcrumbs($language);
        $breadcrumbs[] = new Breadcrumb('Edit Question ('.$language->getName().')', 'quiz_show', ['id' => $this->getQuiz()->getId()]);
        return $breadcrumbs;
    }
}
